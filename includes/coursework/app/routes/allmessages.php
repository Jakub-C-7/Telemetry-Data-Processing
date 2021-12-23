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

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

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
        if(isset ($message['GID']) && $message['GID'] == 'AA' ) {
            $parsed_message_list[] = processMessage($message, $validator);
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
    $processedMessage = [
        'source' => $message['SOURCEMSISDN'],
        'destination' => $message['DESTINATIONMSISDN'],
        'bearer' => $message['BEARER'],
        'ref' => $message['MESSAGEREF']
    ];

    $receivedTime = ($message['RECEIVEDTIME']);
    $processedMessage['received'] = $receivedTime;

    if (isset($message['TMP']) && $validator->validateTemperature($message['TMP']) !== false) {
        $processedMessage['temperature'] = $message['TMP'];
    }else{
        $processedMessage['temperature'] = null;
    }

    if (isset($message['KP']) && $validator->validateKeypad($message['KP']) !== false) {
        $processedMessage['keypad'] = $message['KP'];
    }else{
        $processedMessage['keypad'] = null;
    }

    if (isset($message['FN']) && $validator->validateFan($message['FN']) !== false) {
        $processedMessage['fan'] = $message['FN'];
    }else{
        $processedMessage['fan'] = null;
    }

    if (isset ($message['SW1']) && $validator->validateSwitch($message['SW1']) !== false){
        $processedMessage['switchOne'] = $message['SW1'];
    }else{
        $processedMessage['switchOne'] = null;
    }

    if (isset ($message['SW2']) && $validator->validateSwitch($message['SW2']) !== false){
        $processedMessage['switchTwo'] = $message['SW2'];
    }else{
        $processedMessage['switchTwo'] = null;
    }

    if (isset ($message['SW3']) && $validator->validateSwitch($message['SW3']) !== false){
        $processedMessage['switchThree'] = $message['SW3'];
    }else{
        $processedMessage['switchThree'] = null;
    }

    if (isset ($message['SW4']) && $validator->validateSwitch($message['SW4']) !== false){
        $processedMessage['switchFour'] = $message['SW4'];
    }else{
        $processedMessage['switchFour'] = null;
    }

    return $processedMessage;
}