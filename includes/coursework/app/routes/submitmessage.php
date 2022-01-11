<?php

/**
 * submitmessage.php script sends a message back to the telemetry board to update settings.
 *
 * The message is retrieved from the body, is validated, formatted into a valid XML format, the message is sent,
 * and the action is logged.
 *
 * @author Jakub Chamera
 * Date: 10/01/2022
 */

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app->post('/submitmessage', function(Request $request, Response $response) use ($app) {

    session_start();

    if(!isset($_SESSION['user'])) {
        $response = $response->withRedirect("/coursework_public/startingmenu");
        return $response;

    } else {

        $messageModel = $this->get('messageModel');
        $validator = $this->get('validator');
        $logger = $app->getContainer()->get('telemetryLogger');

        $message = $request->getParsedBody();

        $validatedMessage = validateSentMessage($message, $validator);

        $formattedMessage = "<GID>AA</GID><Switches> <SW1>" . $validatedMessage["switchOne"] . "</SW1>
<SW2>" . $validatedMessage["switchTwo"] . "</SW2><SW3>" . $validatedMessage["switchThree"] . "</SW3>
<SW4>" . $validatedMessage["switchFour"] . "</SW4></Switches><FN>" . $validatedMessage["fan"] . "</FN>
<TMP>" . $validatedMessage["temperature"] . "</TMP><KP>" . $validatedMessage["keypad"] . "</KP>";

        $telemetryBoardPhoneNumber = "447817814149";
        $messageModel->sendMessage("", $telemetryBoardPhoneNumber, $formattedMessage);
        $logger->info('A new message has been sent to the telemetry board');

        return $this->view->render($response,
            'submitmessage.html.twig',
            [
                'Css_path' => CSS_PATH,
                'landing_page' => $_SERVER["SCRIPT_NAME"],
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => 'Send Message',
                'method' => 'post',
                'message' => $formattedMessage
            ]);
    }

})->setName('submitmessage');

/**
 * Function for validating entered message details to be sent.
 * @param array $message Message array containing message content to be sent.
 * @param \Coursework\Validator $validator Instance of the validator class used for validation and sanitisation.
 * @return array Returns an array containing validated and sanitised message.
 */
function validateSentMessage(array $message, \Coursework\Validator $validator): array
{
    $processedMessage = [];

    if (isset($message['temperature']) && $validator->validateTemperature($message['temperature']) !== false) {
        $processedMessage['temperature'] = $message['temperature'];
    } else {
        $processedMessage['temperature'] = null;
    }

    if (isset($message['keypad']) && $validator->validateKeypad($message['keypad']) !== false) {
        $processedMessage['keypad'] = $message['keypad'];
    } else {
        $processedMessage['keypad'] = null;
    }

    if (isset($message['fan']) && $validator->validateFan(strtolower($message['fan'])) !== false) {
        $fn = strtolower($message['fan']);
        if ($fn == 'reverse' || $fn == '0' || $fn == 'false') {
            $processedMessage['fan'] = '0';
        } else {
            $processedMessage['fan'] = '1';
        }
    } else {
        $processedMessage['fan'] = null;
    }

    if (isset ($message['switchOne']) && $validator->validateSwitch(strtolower($message['switchOne']), '1') !== false) {
        $sw1 = strtolower($message['switchOne']);
        if ($sw1 == 'off' || $sw1 == '0' || $sw1 == 'false') {
            $processedMessage['switchOne'] = '0';
        } else {
            $processedMessage['switchOne'] = '1';
        }
    } else {
        $processedMessage['switchOne'] = null;
    }

    if (isset ($message['switchTwo']) && $validator->validateSwitch(strtolower($message['switchTwo']), '2') !== false) {
        $sw2 = strtolower($message['switchTwo']);
        if ($sw2 == 'off' || $sw2 == '0' || $sw2 == 'false') {
            $processedMessage['switchTwo'] = '0';
        } else {
            $processedMessage['switchTwo'] = '1';
        }
    } else {
        $processedMessage['switchTwo'] = null;
    }

    if (isset ($message['switchThree']) && $validator->validateSwitch(strtolower($message['switchThree']), 'three') !== false) {
        $sw3 = strtolower($message['switchThree']);
        if ($sw3 == 'off' || $sw3 == '0' || $sw3 == 'false') {
            $processedMessage['switchThree'] = '0';
        } else {
            $processedMessage['switchThree'] = '1';
        }
    } else {
        $processedMessage['switchThree'] = null;
    }

    if (isset ($message['switchFour']) && $validator->validateSwitch(strtolower($message['switchFour']), 'four') !== false) {
        $sw4 = strtolower($message['switchFour']);
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
