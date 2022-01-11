<?php

/**
 * startingmenu.php script renders the starting menu page.
 *
 * The first page displayed to the users before they are logged in.
 *
 * @author Jakub Chamera
 * Date: 10/01/2021
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/startingmenu', function(Request $request, Response $response) use ($app) {

    session_start();

    if(isset($_SESSION['user'])){
        $response = $response->withRedirect("/coursework_public/");
    } else {
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

