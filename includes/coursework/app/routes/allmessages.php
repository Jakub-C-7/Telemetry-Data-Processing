<?php

/**
 * allmessages.php script retrieves messages from the database.
 *
 * Route renders the allmessages page and calls methods to retrieve downloaded messages from the database.
 *
 */

use Doctrine\DBAL\DriverManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/allmessages', function(Request $request, Response $response) use ($app) {

    session_start();

    $logger = $app->getContainer()->get('telemetryLogger');

    if(!isset($_SESSION['user'])) {
        $response = $response->withRedirect("startingmenu");
        $logger->error('A user attempted to enter the allmessages page but was not logged in');
        return $response;

    } else {
        $messages = retrieveMessages($app);

        if ($messages != false) {
            $logger->info('The user: '. $_SESSION['user']. ' entered the allmessages page');
            createMessageView($app, $response, $messages);
        } else {
            $logger->error('The user: '. $_SESSION['user']. ' entered the allmessages page but there was an error 
            retrieving messages');
            createAllMessagesErrorView($app, $response);
        }
    }
})->setName('allmessages');

/**
 * A function to retrieve messages from the database.
 * @param $app Slim\App The slim application.
 * @return false|mixed An array of messages or false if an error occurs while retrieving messages.
 * @throws \Doctrine\DBAL\Exception
 */
function retrieveMessages($app) {
    $database_connection_settings = $app->getContainer()->get('doctrine_settings');
    $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
    $database_connection = DriverManager::getConnection($database_connection_settings);
    $queryBuilder = $database_connection->createQueryBuilder();

    $message_result = $doctrine_queries::retrieveAllMessages($queryBuilder);

    $logger = $app->getContainer()->get('telemetryLogger');

    if ($message_result['outcome'] !== false) {
        $logger->info('Messages were successfully retrieved using the query: '.$message_result['sql_query']);

        for ($i = 0; $i <= count($message_result['result'])-1; $i++) {
            if ($message_result['result'][$i]['switch1'] == 0) {
                $message_result['result'][$i]['switch1'] = 'off';
            } else {
                $message_result['result'][$i]['switch1'] = 'on';
            }

            if ($message_result['result'][$i]['switch2'] == 0) {
                $message_result['result'][$i]['switch2'] = 'off';
            } else {
                $message_result['result'][$i]['switch2'] = 'on';
            }

            if ($message_result['result'][$i]['switch3'] == 0) {
                $message_result['result'][$i]['switch3'] = 'off';
            } else {
                $message_result['result'][$i]['switch3'] = 'on';
            }

            if ($message_result['result'][$i]['switch4'] == 0) {
                $message_result['result'][$i]['switch4'] = 'off';
            } else {
                $message_result['result'][$i]['switch4'] = 'on';
            }

            if ($message_result['result'][$i]['fan'] == 0) {
                $message_result['result'][$i]['fan'] = 'reverse';
            } else {
                $message_result['result'][$i]['fan'] = 'forward';
            }
        }

        return $message_result['result'];
    } else {
        $logger->error('Error while retrieving messages using query: '.$message_result['sql_query']);
        return false;
    }
}

/**
 * Creates the view on the web page for the messages.
 * @param $app Slim\App The slim application.
 * @param $response Response The response to render.
 * @param $message_list array The list of messages to populate on the front end.
 */
function createMessageView($app, $response, $message_list) {
    $view = $app->getContainer()->get('view');
    $view->render($response,
        'allmessages.html.twig',
        [
            'Css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'page_title' => APP_NAME,
            'page_heading_1' => 'Telemetry Data Processing',
            'page_heading_2' => 'Message 1',
            'page_heading_3' => 'Message Metadata',
            'page_heading_4' => 'Message Content',
            'page_heading_5' => 'Message 2',
            'message_list' => $message_list
        ]
    );
}

/**
 * Creates the error view.
 * @param $app Slim\App The slim application
 * @param $response Response The response of the HTTP request.
 */
function createAllMessagesErrorView($app, $response) {
    $view = $app->getContainer()->get('view');
    $view->render($response,
        'errorpage.html.twig',
        [
            'Css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'page_title' => APP_NAME,
            'page_heading_1' => 'Telemetry Data Processing',
            'message' => 'oops something went wrong while retrieving the messages... try again later'
        ]
    );
}

