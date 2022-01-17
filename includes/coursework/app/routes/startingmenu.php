<?php

/**
 * startingmenu.php script renders the starting menu page for any not logged-in users.
 *
 * This is the first page displayed to the users upon entering the platform and serves as a landing page to provide a
 * greeting.
 *
 * @author Jakub Chamera
 * Date: 10/01/2021
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/startingmenu', function(Request $request, Response $response) use ($app) {

    session_start();

    $logger = $app->getContainer()->get('telemetryLogger');

    if(isset($_SESSION['user'])){
        $logger->error('The user: '. $_SESSION['user']. ' attempted to enter the starting menu page but was already logged in');
        $response = $response->withRedirect("index.php");
    } else {
        $logger->info('A user has entered the starting menu page');
        return $this->view->render($response,
            'startingmenu.html.twig',
            [
                'Css_path' => CSS_PATH,
                'landing_page' => $_SERVER["SCRIPT_NAME"],
                'initial_input_box_value' => null,
                'page_title' => APP_NAME,
                'page_heading_1' => 'Telemetry Data Processing'
            ]);
    }

})->setName('startingmenu');

