<?php
/**
 * homepage.php
 *
 * The route to render the homepage.
 *
 * Testing docblock changes
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function(Request $request, Response $response) use ($app) {

    $messageModel = $this->get('messageModel');
    $xmlParser = $this->get('xmlParser');

    //Calls the method to download messages. The first field takes the username and the second takes number of messages.
    $message_list = $messageModel->downloadMessages('', 20);

    $parsed_message_list = [];
    //Process message content for each retrieved message
    foreach($message_list as $message){
        $message = $xmlParser->parseXmlArray($message);
        $parsed_message_list[] = processMessage($message);
    }

    //calls the createMessageDisplay method that then calls the twig that loops through the message list and displays messages
    createMessageDisplay($app, $response, $parsed_message_list);

})->setName('homepage');


function createMessageDisplay($app, $response, $parsed_message_list): void
{
    $view = $app->getContainer()->get('view');
    $view->render($response,
        'homepageform.html.twig',
        [
            'Css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => 'Telemetry Data Processing',
            'page_heading_2' => 'Message 1',
            'page_heading_3' => 'Message Metadata',
            'page_heading_4' => 'Message Content',
            'info_text' => 'Your information will be stored in either a session file or in a database',
            'method' => 'post',
            'message_list' => $parsed_message_list,
            'page_text' => 'Select a message',
        ]);
}

//Process XML retrieved from SOAP call
function processMessage(array $message): array
{
    //Creating the processes message array to store messages.
    $processedMessage = [
        'source' => $message['SOURCEMSISDN'],
        'destination' => $message['DESTINATIONMSISDN'],
        'bearer' => $message['BEARER'],
        'ref' => $message['MESSAGEREF']
    ];

    $receivedTime = ($message['RECEIVEDTIME']);
    $processedMessage['received'] = $receivedTime;

    if (isset($message['TMP'])) {
        $processedMessage['temperature'] = $message['TMP'];
    }else{
        $processedMessage['temperature'] = 'EMPTY DATA';
    }

    if (isset($message['KP'])) {
        $processedMessage['keypad'] = $message['KP'];
    }else{
        $processedMessage['keypad'] = 'EMPTY DATA';
    }

    if (isset($message['FN'])) {
        $processedMessage['fan'] = $message['FN'];
    }else{
        $processedMessage['fan'] = 'EMPTY DATA';
    }

    if (isset ($message['SW1'])){
        $processedMessage['switchOne'] = $message['SW1'];
    }else{
        $processedMessage['switchOne'] = 'EMPTY DATA';
    }

    if (isset ($message['SW2'])){
        $processedMessage['switchTwo'] = $message['SW2'];
    }else{
        $processedMessage['switchTwo'] = 'EMPTY DATA';
    }

    if (isset ($message['SW3'])){
        $processedMessage['switchThree'] = $message['SW3'];
    }else{
        $processedMessage['switchThree'] = 'EMPTY DATA';
    }

    if (isset ($message['SW4'])){
        $processedMessage['switchFour'] = $message['SW4'];
    }else{
        $processedMessage['switchFour'] = 'EMPTY DATA';
    }

    return $processedMessage;
}
