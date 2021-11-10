<?php

// Register component on container
use Sessions\SessionValidator;
use Sessions\SessionWrapper;

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(
        $container['settings']['view']['template_path'],
        $container['settings']['view']['twig'],
        [
            'debug' => true // This line should enable debug mode
        ]
    );

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['sessionValidator'] = function () {
    $validator = new SessionValidator();
    return $validator;
};

$container['sessionWrapper'] = function () {
    $session_wrapper = new SessionWrapper();
    return $session_wrapper;
};

$container['mysqlWrapper'] = function () {
    $mysql_wrapper = new \Sessions\DatabaseWrapper();
    return $mysql_wrapper;
};

$container['sessionModel'] = function ($container) {
    $session_model = new \Sessions\SessionModel();
    return $session_model;
};

$container['sqlQueries'] = function () {
    $sql_queries = new \Sessions\SQLQueries();
    return $sql_queries;
};

$container['databaseWrapper'] = function ($container) {
    $database_wrapper_handle = new \Sessions\DatabaseWrapper();
    return $database_wrapper_handle;
};

$container['loggerWrapper'] = function ($container) {
    $logging_wrapper = new Monolog\Logger('logger');
    return $logging_wrapper;

};

/**
 * Creates two log handler streams, one for notices (successful database access)
 * one for warnings (database access error)
 *
 * Based upon the example code from lab 3
 *
 * Uses a closure to add information to the output
 *
 * Lots of guidance at http://zetcode.com/php/monolog/ and https://akrabat.com/logging-errors-in-slim-3/
 *
 * @return Logger
 */
$container['sessionLogger'] = function () {
    $logger = new Logger('logger');

    $session_log_notices = LOG_FILE_PATH . 'sessions_notices.log';
    $stream_notices = new StreamHandler($session_log_notices, Logger::NOTICE);
    $logger->pushHandler($stream_notices);

    $session_log_warnings = LOG_FILE_PATH . 'sessions_warnings.log';
    $stream_warnings = new StreamHandler($session_log_warnings, Logger::WARNING);
    $logger->pushHandler($stream_warnings);

    $logger->pushProcessor(function ($record) {
        $record['context']['sid'] = session_id();
        $record['extra']['name'] = 'Clinton';
        return $record;
    });

    return $logger;
};
