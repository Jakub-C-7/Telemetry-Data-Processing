<?php
/**
 * allmessages.php script
 *
 * Route renders the allmessages page. Calls methods to download messages from the EE server, checks if they are meant
 * for the group AA via checking group id (GID), calls validation methods for messages, and displays the relevant
 * messages structured into a table for the user.
 *
 * Author: Jakub Chamera
 * Date: 14/12/2021
 */

use Doctrine\DBAL\DriverManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/allmessages', function(Request $request, Response $response) use ($app) {
    $view = $app->getContainer()->get('view');
    $view->render($response,
        'allmessages.html.twig',
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
//            'message_list' => $parsed_message_list,
        ]);

})->setName('allmessages');
