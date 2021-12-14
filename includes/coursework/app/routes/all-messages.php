<?php
/**
 * all-messages.php
 *
 * The route to render the all-messages page.
 *
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/all-messages', function(Request $request, Response $response)
{
    return $this->view->render($response,
        'all-messages.html.twig',
        [
            'Css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => 'Telemetry Data Processing',
            'page_heading_2' => 'Message 1',
            'page_heading_3' => 'Message Metadata',
            'page_heading_4' => 'Message Content',
            'page_heading_5' => 'Message 2',
        ]);
})->setName('All Messages');