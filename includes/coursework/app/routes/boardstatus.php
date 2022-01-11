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

    session_start();

    if(!isset($_SESSION['user'])) {
        $response = $response->withRedirect("/coursework_public/login");
        return $response;
    } else {
        $latestMessage = retrieveLatestStoredMessage($app);

        if ($latestMessage != false) {
            createBoardStatusView($app, $response, $latestMessage);
        } else if (empty($latestMessage)) {
            $error = 'No message has been retrieved, please try downloading messages first using the 
        Download Messages page.';
            createBoardStatusErrorView($app, $response, $error);
        } else {
            $error = 'Please try again later.';
            createBoardStatusErrorView($app, $response, $error);
        }
    }

})->setName('boardstatus');

/**
 * Function for retrieving the latest valid message from the database.
 * @param $app -The app parameter used to inject dependencies.
 * @return false|mixed - Returns the message array on success and false on failures.
 */
function retrieveLatestStoredMessage($app) {
    $logger = $app->getContainer()->get('telemetryLogger');
    $database_connection_settings = $app->getContainer()->get('doctrine_settings');
    $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
    $database_connection = DriverManager::getConnection($database_connection_settings);
    $queryBuilder = $database_connection->createQueryBuilder();

    $result = $doctrine_queries::retrieveLatestMessage($queryBuilder);

    if (!empty($result['result']) && $result['outcome'] != false) {
        $latestMessage = $result['result'][0];
        $logger->info('The board status was successfully retrieved using the query: '.$result['sql_query']);

        return $latestMessage;
    } else {
        $logger->error('Error while retrieving board status using query: '.$result['sql_query']);
        return false;
    }
}

/**
 * Function for creating the view page upon successful retrieval of latest settings.
 * @param $app - The app parameter used to inject dependencies.
 * @param $response - The response page being returned.
 * @param $latestMessage - The latest message being passed onto the front page to be displayed.
 */
function createBoardStatusView($app, $response, $latestMessage) {
    $view = $app->getContainer()->get('view');
    $view->render($response,
        'boardstatus.html.twig',
        [
            'Css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => 'Current Telemetry Board Status',
            'message' => $latestMessage
        ]
    );
}

/**
 * Function for creating the error view page if retrieval of board settings fails.
 * @param $app - The app parameter used to inject dependencies.
 * @param $response -The response error page being returned.
 */
function createBoardStatusErrorView($app, $response, $error) {
    $view = $app->getContainer()->get('view');
    $view->render($response,
        'errorpage.html.twig',
        [
            'Css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'page_title' => APP_NAME,
            'page_heading_1' => 'Telemetry Data Processing',
            'message' => 'Oops, something went wrong while retrieving the board status. ' .$error
        ]
    );
}

