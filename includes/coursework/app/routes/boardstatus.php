<?php

/**
 * boardstatus.php shows the latest status of the telemetry board.
 *
 * Retrieves the latest valid message from the database using Doctrine and ordering finding the newest message by
 * date time. The message is split up and contents (current board status) are displayed to the user.
 *
 * @author Jakub Chamera
 * Date: 07/01/2021
 */

use Doctrine\DBAL\DriverManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app->get('/boardstatus', function(Request $request, Response $response) use ($app) {

    $database_connection_settings = $app->getContainer()->get('doctrine_settings');
    $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
    $database_connection = DriverManager::getConnection($database_connection_settings);
    $queryBuilder = $database_connection->createQueryBuilder();

    $result = $doctrine_queries::retrieveLatestMessage($queryBuilder);

    $latestMessage = $result['result'][0];

    return $this->view->render($response,
        'boardstatus.html.twig',
        [
            'Css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => 'Current Telemetry Board Status',
            'message' => $latestMessage
        ]);

})->setName('boardstatus');
