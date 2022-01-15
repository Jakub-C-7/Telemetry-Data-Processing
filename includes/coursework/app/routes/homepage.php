<?php

/**
 * homepage.php script renders the homepage for logged in users.
 *
 * The route to render the homepage for the application. Includes an initial greeting for users.
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function(Request $request, Response $response) use ($app) {

    session_start();

    $logger = $app->getContainer()->get('telemetryLogger');

    if(!isset($_SESSION['user'])) {
        $response = $response->withRedirect("startingmenu");
        $logger->error('A user attempted to enter the homepage but was not logged in');
        return $response;

    } else {
        $logger->info('The user: '. $_SESSION['user']. ' entered the homepage');
        return $this->view->render($response,
            'homepageform.html.twig',
            [
                'Css_path' => CSS_PATH,
                'landing_page' => $_SERVER["SCRIPT_NAME"],
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => 'Telemetry Data Processing',
            ]);
    }

})->setName('homepage');

