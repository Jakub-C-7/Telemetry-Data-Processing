<?php

/**
 * login.php script allows users to input and submit details to log into an account.
 *
 * @author Jakub Chamera
 * Date: 08/01/2022
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/login', function (Request $request, Response $response) use ($app) {

    session_start();

    $logger = $app->getContainer()->get('telemetryLogger');

    if(isset($_SESSION['user'])) {
        $response = $response->withRedirect("index.php");
        $logger->error('The user: '. $_SESSION['user']. ' attempted to enter the login page but was already logged in');
        return $response;
    } else {
        $logger->info('A user has entered the login page');
        return $this->view->render($response,
            'login.html.twig',
            [
                'Css_path' => CSS_PATH,
                'landing_page' => $_SERVER["SCRIPT_NAME"],
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => 'Login',
                'action' => 'submitlogin',
                'method' => 'post'
            ]);
    }

})->setName('login');

