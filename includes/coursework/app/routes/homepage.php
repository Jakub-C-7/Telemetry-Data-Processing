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

    //Calls the method to download messages. The first field takes the username and the second takes number of messages.
    $message_list = $messageModel->downloadMessages('', 20);

    $parsed_message_list = [];
    //Process message content for each retrieved message
        foreach ($message_list as $message) {
            $message = $xmlParser->parseXmlArray($message);
            if(isset ($message['GID']) && $message['GID'] == 'AA' ) {
                $parsed_message_list[] = processMessage($message);
            }
        }

    //calls the createMessageDisplay method that then calls the twig that loops through the message list and displays messages
    createMessageDisplay($app, $response, $parsed_message_list);
    $cleaned_parameters = cleanupParameters($app, $parsed_message_list);

    if ($cleaned_parameters['sanitised_source'] == false ||
        $cleaned_parameters['sanitised_destination'] == false ||
        $cleaned_parameters['sanitised_received'] == false ||
        $cleaned_parameters['sanitised_bearer'] == false ||
        $cleaned_parameters['sanitised_ref'] == false ||
        $cleaned_parameters['sanitised_switchOne'] == null ||
        $cleaned_parameters['sanitised_switchTwo'] == null ||
        $cleaned_parameters['sanitised_switchThree'] == null ||
        $cleaned_parameters['sanitised_switchFour'] == null ||
        $cleaned_parameters['sanitised_fan'] == null ||
        $cleaned_parameters['sanitised_temperature'] == false ||
        $cleaned_parameters['sanitised_keypad'] == false
    ) {
       // TODO: Add logging here
       // $log->error('Error: Inputs were incorrect.');

        return $this->view->render($response,
            'display_invalid_data_error.html.twig',
            [

            ]
        );
    }

})->setName('homepage');

function cleanUpParameters($app, $tainted_parameters) {
    $cleaned_parameters = [];
    $validator = $app->getContainer()->get('validator');

    $tainted_source = $tainted_parameters['source'];
    $tainted_destination = $tainted_parameters['destination'];
    $tainted_message_received_time = $tainted_parameters['received'];
    $tainted_bearer = $tainted_parameters['bearer'];
    $tainted_message_ref = $tainted_parameters['ref'];
    $tainted_switch1 = $tainted_parameters['switchOne'];
    $tainted_switch2 = $tainted_parameters['switchTwo'];
    $tainted_switch3 = $tainted_parameters['switchThree'];
    $tainted_switch4 = $tainted_parameters['switchFour'];
    $tainted_fan = $tainted_parameters['fan'];
    $tainted_temperature = $tainted_parameters['temperature'];
    $tainted_keypad = $tainted_parameters['keypad'];

    $cleaned_parameters['sanitised_source'] = $validator->sanitiseString($tainted_source);
    $cleaned_parameters['sanitised_destination'] = $validator->sanitiseString($tainted_destination);
    $cleaned_parameters['sanitised_received'] = $validator->sanitiseString($tainted_message_received_time);
    $cleaned_parameters['sanitised_bearer'] = $validator->sanitiseString($tainted_bearer);
    $cleaned_parameters['sanitised_ref'] = $validator->sanitiseString($tainted_message_ref);
    $cleaned_parameters['sanitised_switchOne'] = $validator->sanitiseBoolean($tainted_switch1);
    $cleaned_parameters['sanitised_switchTwo'] = $validator->sanitiseBoolean($tainted_switch2);
    $cleaned_parameters['sanitised_switchThree'] = $validator->sanitiseBoolean($tainted_switch3);
    $cleaned_parameters['sanitised_switchFour'] = $validator->sanitiseBoolean($tainted_switch4);
    $cleaned_parameters['sanitised_fan'] = $validator->sanitiseBoolean($tainted_fan);
    $cleaned_parameters['sanitised_temperature'] = $validator->sanitiseString($tainted_temperature);
    $cleaned_parameters['sanitised_keypad'] = $validator->sanitiseString($tainted_keypad);

    return $cleaned_parameters;
}

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
function processMessage(array $message): array
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
