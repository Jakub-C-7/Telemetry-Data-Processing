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
//    $xmlParser = $this->get('xmlParser');

    //Calls the method to download messages. The first field takes the username and the second takes number of messages.
    $message_list = $messageModel->downloadMessages('', 15);

    //calls the createMessageDisplay method that then calls the twig that loops through the message list and spits it out onto the screen.
    createMessageDisplay($app, $response, $message_list);

})->setName('homepage');


function createMessageDisplay($app, $response, $message_list): void
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
            'message_list' => $message_list,
            'page_text' => 'Select a message',
        ]);
}