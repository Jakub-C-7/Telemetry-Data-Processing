<?php
/**
 * Dependencies File
 * PHP file injects dependencies in the form of containers.
 * Classes, views, logger.
 *
 * Author: Jakub Chamera
 * Date: 17/11/2021
 */
use Sessions\SessionValidator;
use Sessions\SessionWrapper;
use Coursework\SoapWrapper;

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

//$container['Validator'] = function () {
//    $validator = new \Coursework\Validator();
//    return $validator;
//};

//$container['SessionWrapper'] = function () {
//    $session_wrapper = new SessionWrapper();
//    return $session_wrapper;
//};

//$container['MysqlWrapper'] = function () {
//    $mysql_wrapper = new \Coursework\DatabaseWrapper();
//    return $mysql_wrapper;
//};


$container['messageModel'] = function ($container) {
    $message_model = new \Coursework\MessageModel(
        $container['soapWrapper'],
        $container['settings']['soap']['login']
    );
    return $message_model;
};

$container['DoctrineSqlQueries'] = function () {
    $sql_queries = new \Coursework\DoctrineSQLQueries();
    return $sql_queries;
};

//$container['databaseWrapper'] = function ($container) {
//    $database_wrapper_handle = new \Coursework\DatabaseWrapper();
//    return $database_wrapper_handle;
//};

//$container['loggerWrapper'] = function ($container) {
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
//$container['sessionLogger'] = function () {
//    $logger = new Logger('logger');
//
//    $session_log_notices = LOG_FILE_PATH . 'sessions_notices.log';
//    $stream_notices = new StreamHandler($session_log_notices, Logger::NOTICE);
//    $logger->pushHandler($stream_notices);
//
//    $session_log_warnings = LOG_FILE_PATH . 'sessions_warnings.log';
//    $stream_warnings = new StreamHandler($session_log_warnings, Logger::WARNING);
//    $logger->pushHandler($stream_warnings);
//
//    $logger->pushProcessor(function ($record) {
//        $record['context']['sid'] = session_id();
//        $record['extra']['name'] = 'Clinton';
//        return $record;
//    });
//
//    return $logger;
//};
