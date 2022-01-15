<?php

/**
 * sendmessage.php script enables an input and submission of a new message.
 *
 * The page allows for an input of a new message including HTML validation. The submission of this information sends a
 * post request and redirects to the submitmessage.php page to parse, validate, format, and send the message.
 *
 * @author Jakub Chamera
 * Date: 04/01/2021
 */

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app->get('/sendmessage', function(Request $request, Response $response) use ($app) {

    session_start();

    $logger = $app->getContainer()->get('telemetryLogger');

    if(!isset($_SESSION['user'])) {
        $response = $response->withRedirect("startingmenu");
        $logger->error('A user attempted to enter the sendmessage page but was not logged in');
        return $response;
    } else {
        $logger->info('The user: '. $_SESSION['user']. ' entered the sendmessage page');
        return $this->view->render($response,
            'sendmessage.html.twig',
            [
                'Css_path' => CSS_PATH,
                'landing_page' => $_SERVER["SCRIPT_NAME"],
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => 'Send Message',
                'action' => 'submitmessage',
                'method' => 'post'
            ]);
    }

})->setName('sendmessage');

