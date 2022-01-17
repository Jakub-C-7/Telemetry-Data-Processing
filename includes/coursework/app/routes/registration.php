<?php

/**
 * registration.php script allows users to input and submit details for account creation.
 *
 * The input details get passed onto the submitregistration page which processes them in the attempt to register and
 * create a new account.
 *
 * @author Jakub Chamera
 * Date: 07/01/2022
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/registration', function (Request $request, Response $response) use ($app) {

    session_start();

    $logger = $app->getContainer()->get('telemetryLogger');

    if(isset($_SESSION['user'])) {
        $response = $response->withRedirect("index.php");
        $logger->error('The user: '. $_SESSION['user']. ' attempted to enter the registration page but was already 
        logged in');
        return $response;
    } else {
        $logger->info('A user entered the registration page');
        $errors = "";

        return $this->view->render($response,
            'registration.html.twig',
            [
                'Css_path' => CSS_PATH,
                'landing_page' => $_SERVER["SCRIPT_NAME"],
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => 'Registration',
                'action' => 'submitregistration',
                'method' => 'post',
                'errors' => $errors
            ]);
    }

})->setName('registration');

