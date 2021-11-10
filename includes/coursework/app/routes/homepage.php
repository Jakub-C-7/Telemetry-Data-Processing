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

$app->get('/', function(Request $request, Response $response)
{
    return $this->view->render($response,
        'homepageform.html.twig',
        [
            'css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'initial_input_box_value' => null,
            'page_title' => 'Secure Web Application',
            'page_heading_1' => 'Telemetry Data Processing',
            'page_heading_2' => 'Test123',
            'page_heading_3' => 'Test123',
            'info_text' => 'Your information will be stored in either a session file or in a database',
            'sid_text' => 'Your super secret session SID is ',
        ]);
})->setName('homepage');
