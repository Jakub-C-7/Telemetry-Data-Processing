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

    if(!isset($_SESSION['user'])) {
        $response = $response->withRedirect("/coursework_public/startingmenu");
        return $response;

    } else {
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
