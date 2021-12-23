<?php
/**
 * homepage.php
 *
 * The route to render the homepage.
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function(Request $request, Response $response) use ($app) {

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
                $processedMessage = processMessage($message, $validator);
                if( $processedMessage['temperature'] != null &&
                    $processedMessage['keypad'] != null &&
                    $processedMessage['fan'] != null &&
                    $processedMessage['switchOne'] != null &&
                    $processedMessage['switchTwo'] != null &&
                    $processedMessage['switchThree'] != null &&
                    $processedMessage['switchFour'] != null &&
                    $processedMessage['source'] != null &&
                    $processedMessage['destination'] != null &&
                    $processedMessage['bearer'] != null &&
                    $processedMessage['ref'] != null &&
                    $processedMessage['received'] != null
                )
                {
                    $parsed_message_list[] = $processedMessage;
                }
            }
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
       
            'message_list' => $parsed_message_list,

            'page_text' => 'Select a message',
        ]);
}

//Process XML retrieved from SOAP call
function processMessage(array $message, \Coursework\Validator $validator): array
{
    //Creating the processed message array to store messages.
    $processedMessage = [
        'ref' => $message['MESSAGEREF']
    ];

    $receivedTime = ($message['RECEIVEDTIME']);
    $processedMessage['received'] = $receivedTime;

    if (isset($message['BEARER']) && $validator->validateBearer($message['BEARER']) !== false)
    {
        $processedMessage['bearer'] = $message['BEARER'];
    } else {
        $processedMessage['bearer'] = null;
    }

    if (isset($message['SOURCEMSISDN']) && $validator->validatePhoneNumber($message['SOURCEMSISDN']) !== false)
    {
        $processedMessage['source'] = $message['SOURCEMSISDN'];
    } else {
        $processedMessage['source'] = null;
    }

    if (isset($message['DESTINATIONMSISDN']) && $validator->validatePhoneNumber($message['DESTINATIONMSISDN']) !== false)
    {
        $processedMessage['destination'] = $message['DESTINATIONMSISDN'];
    } else {
        $processedMessage['destination'] = null;
    }

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
