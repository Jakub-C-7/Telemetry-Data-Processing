<?php
/**
 * allmessages.php script
 *
 * Route renders the allmessages page. Calls methods to download messages from the EE server, checks if they are meant
 * for the group AA via checking group id (GID), calls validation methods for messages, and displays the relevant
 * messages structured into a table for the user.
 *
 * Author: Jakub Chamera
 * Date: 14/12/2021
 */

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/allmessages', function(Request $request, Response $response) use ($app) {

    $messageModel = $this->get('messageModel');
    $xmlParser = $this->get('xmlParser');
    $validator = $this->get('validator');

    //Calls the method to download messages. The first field takes the username and the second takes number of messages.
    $message_list = $messageModel->downloadMessages('', 20);

    $parsed_message_list = [];
    //Process message content for each retrieved message
    foreach ($message_list as $message) {
        $message = $xmlParser->parseXmlArray($message);

        if (isset ($message['GID']) && $message['GID'] == 'AA' ) {
            $processedMessage = processMessage($message, $validator);

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
            } else {
                var_dump($processedMessage);
            }
        }
    }

    //calls the createMessageDisplay method that then calls the twig that loops through the message list and displays messages
    createMessageDisplay($app, $response, $parsed_message_list);

})->setName('allmessages');


function createMessageDisplay($app, $response, $parsed_message_list): void
{
    $view = $app->getContainer()->get('view');
    $view->render($response,
        'allmessages.html.twig',
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
        $processedMessage['fan'] = $message['FN'];
    } else {
        $processedMessage['fan'] = null;
    }

    if (isset ($message['SW1']) && $validator->validateSwitch(strtolower($message['SW1']), 'switchOne') !== false) {
        $processedMessage['switchOne'] = $message['SW1'];
    } else {
        $processedMessage['switchOne'] = null;
    }

    if (isset ($message['SW2']) && $validator->validateSwitch(strtolower($message['SW2']), 'switchTwo') !== false) {
        $processedMessage['switchTwo'] = $message['SW2'];
    } else {
        $processedMessage['switchTwo'] = null;
    }

    if (isset ($message['SW3']) && $validator->validateSwitch(strtolower($message['SW3']), 'switchThree') !== false) {
        $processedMessage['switchThree'] = $message['SW3'];
    } else {
        $processedMessage['switchThree'] = null;
    }

    if (isset ($message['SW4']) && $validator->validateSwitch(strtolower($message['SW4']), 'switchFour') !== false) {
        $processedMessage['switchFour'] = $message['SW4'];
    } else {
        $processedMessage['switchFour'] = null;
    }

    return $processedMessage;
}
