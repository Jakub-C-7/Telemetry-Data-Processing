<?php

/**
 * downloadmessages.php script downloads messages and displays them.
 *
 * Renders the downloadmessages page, calls methods to download messages from the EE server using SOAP, checks if they
 * are meant for the group 'AA' via checking group id (GID), calls validation for messages, stores the messages in the
 * database, and displays the relevant messages structured into a table for the user.
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

    session_start();

    if(!isset($_SESSION['user'])) {
        $response = $response->withRedirect("startingmenu");
        return $response;
    } else {
        $messageModel = $this->get('messageModel');
        $xmlParser = $this->get('xmlParser');
        $validator = $this->get('validator');
        $logger = $app->getContainer()->get('telemetryLogger');

        $database_connection_settings = $app->getContainer()->get('doctrine_settings_dev');
        $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
        $database_connection = DriverManager::getConnection($database_connection_settings);
        $queryBuilder = $database_connection->createQueryBuilder();

        $message_list = $messageModel->downloadMessages('', 30);

        if ($message_list != null) {
            $parsed_message_list = [];
            foreach ($message_list as $message) {
                $message = $xmlParser->parseXmlArray($message);

                if (isset ($message['GID']) && $message['GID'] == 'AA') {
                    $processedMessage = processMessage($message, $validator);

                    $logger = $app->getContainer()->get('telemetryLogger');

                    if ($processedMessage['temperature'] !== null &&
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

                        $logger->info('Validation has been passed for message');

                        storeNewMessage($app, $processedMessage);
                    } else {
                        $logger->error('Validation not passed for message.');
                    }
                }
            }
            $confirmationUser = $_SESSION['user'];

            $confirmationMessage = ('Hello '. $confirmationUser.'. This is a confirmation message to state that messages '
                .'have been downloaded and there are ' . count($parsed_message_list) . ' valid messages for team AA '.
                'out of a total of ' . count($message_list) . " messages.");
//            $confirmationNumber = "447817814149"; // TELEMETRY BOARD PHONE NUMBER
            $result = $doctrine_queries->getUserPhoneNumber($queryBuilder, $confirmationUser);

            $confirmationNumber = $result['result'][0]['phoneNumber'];

            $messageModel->sendMessage($confirmationUser, $confirmationNumber, $confirmationMessage);

            $logger->info('A confirmation message has been sent to user: '. $confirmationUser . ', on the number: '.
            $confirmationNumber);

            createMessageDisplay($app, $response, $parsed_message_list, $confirmationUser);
        } else {
            createDownloadMessagesErrorView($app, $response);
        }
    }

})->setName('downloadmessages');

/**
 * Function for creating the display after messages have been successfully downloaded.
 * @param $app -Instance of the app used to inject dependencies (view).
 * @param $response -The response page being returned and rendered.
 * @param $parsed_message_list -Parsed list of messages that were downloaded and will be displayed to the user.
 * @param $user -Email string of the user that's currently logged in.
 */
function createMessageDisplay($app, $response, $parsed_message_list, $user): void
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
            'message_count' => count($parsed_message_list),
            'user' => $user
        ]);
}

//TODO: Display relevant errors and what went wrong
function createDownloadMessagesErrorView($app, $response) {
    $view = $app->getContainer()->get('view');
    $view->render($response,
        'errorpage.html.twig',
        [
            'Css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'page_title' => APP_NAME,
            'page_heading_1' => 'Telemetry Data Processing',
            'message' => 'oops something went wrong while downloading messages... try again later'
        ]
    );
}

//TODO: Docblock
function storeNewMessage($app, $message)
{
    $logger = $app->getContainer()->get('telemetryLogger');

    $storage_result = [];
    $store_result = '';

    $database_connection_settings = $app->getContainer()->get('doctrine_settings_dev');
    $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
    $database_connection = DriverManager::getConnection($database_connection_settings);
    $queryBuilder = $database_connection->createQueryBuilder();

    $exists = $doctrine_queries::checkMessageExists($queryBuilder,
        $message['source'],
        $message['destination'],
        $message['received']
    );
    // Query builder is remade because of warnings about string offsets.
    // Remaking it resets the query builder which solves the problem.
    if ($exists == false) {
        $queryBuilder = $database_connection->createQueryBuilder();
        $sender_exists = $doctrine_queries::checkMobileNumberExists($queryBuilder, $message['source']);

        if ($sender_exists == false) {
            $sender_result = $doctrine_queries::insertMobileNumber($queryBuilder, $message['source']);

            if ($sender_result['outcome'] == 1) {
                $logger->info('Mobile number was successfully stored using the query ' . $sender_result['sql_query']);
            } else {
                $logger->error('Problem when storing the mobile number.');
            }
        }

        if (($message['destination'] !== $message['source']) && ($sender_result['outcome'] == 1)) {
            $queryBuilder = $database_connection->createQueryBuilder();
            $recipient_exists = $doctrine_queries::checkMobileNumberExists($queryBuilder, $message['destination']);

            if ($recipient_exists == false) {
                $recipient_result = $doctrine_queries::insertMobileNumber($queryBuilder, $message['destination']);

                if ($recipient_result['outcome'] == 1) {
                    $logger->info(
                        'Mobile number was successfully stored using the query ' . $recipient_result['sql_query']
                    );

                } else {
                    $logger->error('Problem when storing the mobile number.');
                }
            }
        }

        $queryBuilder = $database_connection->createQueryBuilder();
        $message_result = $doctrine_queries::insertMessageData($queryBuilder, $message);

        if ($message_result['outcome'] == 1) {
            $logger->info('Message data was successfully stored using the query ' . $message_result['sql_query']);
        } else {
            $logger->error('Problem when storing message data.');
        }
    } else {
        $logger->info('Message already exists and has not been stored.');
    }

    return false;
}

/**
 * Function for processing/parsing XML strings of message content and metadata into relevant string elements.
 * @param array $message The XML message string in an array to be processed/parsed.
 * @param \Coursework\Validator $validator An instance of the validator being used to sanitise and validate content.
 * @return array Return an array of strings with the parsed data.
 */
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

    if (isset($message['SOURCEMSISDN']) && $validator->validatePhoneNumber($message['SOURCEMSISDN'], 'source')
        !== false) {
        $processedMessage['source'] = $message['SOURCEMSISDN'];
    } else {
        $processedMessage['source'] = null;
    }

    if (isset($message['DESTINATIONMSISDN']) && $validator->validatePhoneNumber($message['DESTINATIONMSISDN'],
            'destination') !== false) {
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

    if (isset ($message['SW3']) && $validator->validateSwitch(strtolower($message['SW3']),'three') !== false){
        $sw3 = strtolower($message['SW3']);
        if ($sw3 == 'off' || $sw3 == '0' || $sw3 == 'false') {
            $processedMessage['switchThree'] = '0';
        } else {
            $processedMessage['switchThree'] = '1';
        }
    } else {
        $processedMessage['switchThree'] = null;
    }

    if (isset ($message['SW4']) && $validator->validateSwitch(strtolower($message['SW4']), 'four') !== false){
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

