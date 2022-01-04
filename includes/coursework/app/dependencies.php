<?php

use Coursework\SoapWrapper;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Dependencies.php Script
 *
 * Injects dependencies in the form of containers. Classes, views, logger.
 *
 * @author Jakub Chamera
 * Date: 17/11/2021
 */

/**
 * @param $container
 * @return \Slim\Views\Twig
 */
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

$container['validator'] = function () {
    $validator = new \Coursework\Validator();
    return $validator;
};

//$container['SessionWrapper'] = function () {
//    $session_wrapper = new SessionWrapper();
//    return $session_wrapper;
//};

$container['messageModel'] = function ($container) {
    $message_model = new \Coursework\MessageModel(
        $container['soapWrapper'],
        $container['settings']['soap']['login']
    );
    return $message_model;
};

$container['doctrineSqlQueries'] = function () {
    $doctrine_sql_queries = new \Coursework\DoctrineSqlQueries();
    return $doctrine_sql_queries;
};

//$container['DatabaseWrapper'] = function ($container) {
//    $database_wrapper_handle = new \Coursework\DatabaseWrapper();
//    return $database_wrapper_handle;
//};

//$container['LoggerWrapper'] = function ($container) {
//    $logging_wrapper = new Monolog\Logger('logger');
//    return $logging_wrapper;
//};

$container['soapWrapper'] = function ($container) {
    $soapWrapper = new \Coursework\SoapWrapper($container['settings']['soap']['connection']);
    return $soapWrapper;
};

$container['xmlParser'] = function ($container) {
    $xmlParser = new \Coursework\XmlParser();
    return $xmlParser;
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
$container['telemetaryLogger'] = function () {
    $logger = new Logger('logger');

//    $telemetary_log_notices = LOG_FILE_PATH . 'telemetary_notices.log';
//    $stream_notices = new StreamHandler($telemetary_log_notices, Logger::NOTICE);
//    $logger->pushHandler($stream_notices);

//    $telemetary_log_warnings = LOG_FILE_PATH . 'telemetary_warnings.log';
//    $stream_warnings = new StreamHandler($telemetary_log_warnings, Logger::WARNING);
//    $logger->pushHandler($stream_warnings);

    $telemetary_log_info = LOG_FILE_PATH . 'telemetary_info.log';
    $stream_infos = new StreamHandler($telemetary_log_info, Logger::INFO);
    $logger->pushHandler($stream_infos);

    $telemetary_log_error = LOG_FILE_PATH . 'telemetary_error.log';
    $stream_errors = new StreamHandler($telemetary_log_error, Logger::ERROR);
    $logger->pushHandler($stream_errors);

    $logger->pushProcessor(function ($record) {
        $record['extra']['name'] = 'AA';
        return $record;
    });

    return $logger;
};
