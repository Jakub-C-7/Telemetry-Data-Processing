<?php

/**
 * downloadmessages.php script renders the downloadmessages page.
 *
 * Calls methods to download messages from the EE server using SOAP, checks if they are meant for the group 'AA'
 * via checking group id (GID), calls validation for messages, stores the messages in the database, and displays the
 * relevant messages structured into a table for the user.
 *
 * @author Jakub Chamera
 * Date: 14/12/2021
 */

use Doctrine\DBAL\DriverManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require 'vendor/autoload.php';

$app->get('/downloadmessages', function(Request $request, Response $response) use ($app) {

    $messageModel = $this->get('messageModel');
    $xmlParser = $this->get('xmlParser');
    $validator = $this->get('validator');

    //Calls the method to download messages. The first field takes the username and the second takes number of messages.
    $message_list = $messageModel->downloadMessages('', 20);
    $parsed_message_list = [];
    //Process message content for each retrieved message
    foreach ($message_list as $message) {
        $message = $xmlParser->parseXmlArray($message);
        var_dump($message);
        if (isset ($message['GID']) && $message['GID'] == 'AA' ) {
            $processedMessage = processMessage($message, $validator);
            var_dump($processedMessage);
            if ( $processedMessage['temperature'] !== null &&
                $processedMessage['keypad'] !== null &&
                $processedMessage['fan'] !== null &&
                $processedMessage['switchOne'] !== null &&
                $processedMessage['switchTwo'] !== null &&
                $processedMessage['switchThree'] !== null &&
                $processedMessage['switchFour'] !== null &&
                $processedMessage['source'] !== null &&
                $processedMessage['destination'] !== null &&
                $processedMessage['bearer'] !== null &&
                $processedMessage['ref'] !== null &&
                $processedMessage['received'] !== null
            ) {
                $parsed_message_list[] = $processedMessage;
                //TODO: Add logging here
                $logger = $app->getContainer()->get('telemetaryLogger');
                $logger->info('Validation has been passed for message');

                storeNewMessage($app, $processedMessage);
            } else {
                //TODO: Log failure
                $logger = $app->getContainer()->get('telemetaryLogger');
                $logger->error('Validation not passed for message.');
            }
        }
    }

    $confirmationMessage = ('This is a confirmation message to state that there are ' . count($parsed_message_list)
        . " valid messages for team AA out of a total of " . count($message_list) . " messages.");
    $confirmationNumber = "447817814149";
    $messageModel->sendMessage('', $confirmationNumber, $confirmationMessage);
    //TODO: Log that a message has been sent

    //calls the createMessageDisplay method that then calls the twig that loops through the message list and displays messages
    createMessageDisplay($app, $response, $parsed_message_list);

})->setName('downloadmessages');

function storeNewMessage($app, $message)
{
    $storage_result = [];
    $store_result = '';

    $database_connection_settings = $app->getContainer()->get('doctrine_settings');
    $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
    $database_connection = DriverManager::getConnection($database_connection_settings);
    $queryBuilder = $database_connection->createQueryBuilder();

    $exists = $doctrine_queries::checkMessageExists($queryBuilder,
        $message['source'],
        $message['destination'],
        $message['received']
    );
    // Query builder is remade because of warnings about string offsets. Remaking it resets the query builder which solves the problem.
    if ($exists == false) {
        $queryBuilder = $database_connection->createQueryBuilder();
        $sender_exists = $doctrine_queries::checkMobileNumberExists($queryBuilder, $message['source']);

        if ($sender_exists == false) {
            $sender_result = $doctrine_queries::insertMobileNumber($queryBuilder, $message['source']);

            if ($sender_result['outcome'] == 1) {
                //TODO: Log success
                $logger = $app->getContainer()->get('telemetaryLogger');
                $logger->info('Mobile number was successfully stored using the query '.$sender_result['sql_query']);
            } else {
                //TODO: Log failure
                $logger = $app->getContainer()->get('telemetaryLogger');
                $logger->error('Problem when storing the mobile number.');
            }
        }

        if (($message['destination'] !== $message['source']) && ($sender_result['outcome'] == 1)) {
            $queryBuilder = $database_connection->createQueryBuilder();
            $recipient_exists = $doctrine_queries::checkMobileNumberExists($queryBuilder, $message['destination']);

            if ($recipient_exists == false) {
                $recipient_result = $doctrine_queries::insertMobileNumber($queryBuilder, $message['destination']);

                if ($recipient_result['outcome'] == 1) {
                    //TODO: Log success
                    $logger = $app->getContainer()->get('telemetaryLogger');
                    $logger->info('Mobile number was successfully stored using the query '.$recipient_result['sql_query']);
                } else {
                    //TODO: Log failure
                    $logger = $app->getContainer()->get('telemetaryLogger');
                    $logger->error('Problem when storing the mobile number.');
                }
            }
        }

        $queryBuilder = $database_connection->createQueryBuilder();
        $message_result = $doctrine_queries::insertMessageData($queryBuilder, $message);

        if ($message_result['outcome'] == 1) {
            //TODO: Log success
            $logger = $app->getContainer()->get('telemetaryLogger');
            $logger->info('Message data was successfully stored using the query '.$message_result['sql_query']);
        } else {
            //TODO: Log failure
            $logger = $app->getContainer()->get('telemetaryLogger');
            $logger->info('Problem when storing message data.');

        }
    } else {
        //TODO: Add this message to a log
        $logger = $app->getContainer()->get('telemetaryLogger');
        $logger->info('Message already exists and has not been stored.');
    }

    return false;
}

function createMessageDisplay($app, $response, $parsed_message_list): void
{
    $view = $app->getContainer()->get('view');
    $view->render($response,
        'downloadmessages.html.twig',
        [
            'Css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => 'Telemetry Data Processing',
            'page_heading_2' => 'Message 1',
            'page_heading_3' => 'Message Metadata',
            'page_heading_4' => 'Message Content',
            'page_heading_5' => 'Message 2',
            'message_list' => $parsed_message_list,
        ]);
}

//Process XML retrieved from SOAP call
function processMessage(array $message, \Coursework\Validator $validator): array
{
    //Creating the processed message array to store messages.
    if (isset($message['MESSAGEREF']) && $validator->validateMessageRef($message['MESSAGEREF']) !== false) {
        $processedMessage['ref'] = $message['MESSAGEREF'];
    } else {
        $processedMessage['ref'] = null;
    }

    if (isset($message['RECEIVEDTIME']) && $validator->validateDateTime($message['RECEIVEDTIME']) !== false) {
        $processedMessage['received'] = $message['RECEIVEDTIME'];
    } else {
        $processedMessage['received'] = null;
    }

    if (isset($message['BEARER']) && $validator->validateBearer(strtolower($message['BEARER'])) !== false) {
        $processedMessage['bearer'] = $message['BEARER'];
    } else {
        $processedMessage['bearer'] = null;
    }

    if (isset($message['SOURCEMSISDN']) && $validator->validatePhoneNumber($message['SOURCEMSISDN'], 'source') !== false) {
        $processedMessage['source'] = $message['SOURCEMSISDN'];
    } else {
        $processedMessage['source'] = null;
    }

    if (isset($message['DESTINATIONMSISDN']) && $validator->validatePhoneNumber($message['DESTINATIONMSISDN'], 'destination') !== false) {
        $processedMessage['destination'] = $message['DESTINATIONMSISDN'];
    } else {
        $processedMessage['destination'] = null;
    }

    if (isset($message['TMP']) && $validator->validateTemperature($message['TMP']) !== false) {
        $processedMessage['temperature'] = $message['TMP'];
    } else {
        $processedMessage['temperature'] = null;
    }

    if (isset($message['KP']) && $validator->validateKeypad($message['KP']) !== false) {
        $processedMessage['keypad'] = $message['KP'];
    } else {
        $processedMessage['keypad'] = null;
    }

    if (isset($message['FN']) && $validator->validateFan(strtolower($message['FN'])) !== false) {
        $fn = strtolower($message['FN']);
        if ($fn == 'reverse' || $fn == '0' || $fn == 'false') {
            $processedMessage['fan'] = '0';
        } else {
            $processedMessage['fan'] = '1';
        }
    } else {
        $processedMessage['fan'] = null;
    }

    if (isset ($message['SW1']) && $validator->validateSwitch(strtolower($message['SW1']), '1') !== false) {
        $sw1 = strtolower($message['SW1']);
        if ($sw1 == 'off' || $sw1 == '0' || $sw1 == 'false') {
            $processedMessage['switchOne'] = '0';
        } else {
            $processedMessage['switchOne'] = '1';
        }
    } else {
        $processedMessage['switchOne'] = null;
    }

    if (isset ($message['SW2']) && $validator->validateSwitch(strtolower($message['SW2']), '2') !== false) {
        $sw2 = strtolower($message['SW2']);
        if ($sw2 == 'off' || $sw2 == '0' || $sw2 == 'false') {
            $processedMessage['switchTwo'] = '0';
        } else {
            $processedMessage['switchTwo'] = '1';
        }
    } else {
        $processedMessage['switchTwo'] = null;
    }

    if (isset ($message['SW3']) && $validator->validateSwitch(strtolower($message['SW3']), 'three') !== false) {
        $sw3 = strtolower($message['SW3']);
        if ($sw3 == 'off' || $sw3 == '0' || $sw3 == 'false') {
            $processedMessage['switchThree'] = '0';
        } else {
            $processedMessage['switchThree'] = '1';
        }
    } else {
        $processedMessage['switchThree'] = null;
    }

    if (isset ($message['SW4']) && $validator->validateSwitch(strtolower($message['SW4']), 'four') !== false) {
        $sw4 = strtolower($message['SW4']);
        if ($sw4 == 'off' || $sw4 == '0' || $sw4 == 'false') {
            $processedMessage['switchFour'] = '0';
        } else {
            $processedMessage['switchFour'] = '1';
        }
    } else {
        $processedMessage['switchFour'] = null;
    }

    return $processedMessage;
}
