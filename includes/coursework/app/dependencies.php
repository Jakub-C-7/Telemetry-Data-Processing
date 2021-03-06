<?php

/**
 * Dependencies.php Script injects dependencies.
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

use Coursework\SoapWrapper;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(
        $container['settings']['view']['template_path'],
        $container['settings']['view']['twig'],
        [
            'debug' => true // This line should enable debug mode
        ]
    );

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()),
        '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

/**
 * Creates two log handler streams, one for notices (e.g. successful database access) and one for warnings (e.g.
 * database access error).
 *
 * Based upon the example code from lab 3.
 *
 * Uses a closure to add information to the output.
 *
 * Lots of guidance at http://zetcode.com/php/monolog/ and https://akrabat.com/logging-errors-in-slim-3/
 *
 * @return Logger An instance of the logger class.
 */
$container['telemetryLogger'] = function () {
    $logger = new Logger('logger');

    $telemetry_log_info = LOG_FILE_PATH . 'telemetry_info.log';
    $stream_infos = new StreamHandler($telemetry_log_info, Logger::INFO);
    $logger->pushHandler($stream_infos);

    $telemetry_log_error = LOG_FILE_PATH . 'telemetry_error.log';
    $stream_errors = new StreamHandler($telemetry_log_error, Logger::ERROR, false);
    $logger->pushHandler($stream_errors);

    $logger->pushProcessor(function ($record) {
        $record['extra']['name'] = 'AA';
        return $record;
    });

    return $logger;
};

$container['validator'] = function () {
    $validator = new \Coursework\Validator();
    return $validator;
};

$container['bcryptWrapper'] = function () {
    $bcryptWrapper = new \Coursework\BcryptWrapper();
    return $bcryptWrapper;
};

$container['base64Wrapper'] = function () {
    $base64Wrapper = new \Coursework\Base64Wrapper();
    return $base64Wrapper;
};
$container['libSodiumWrapper'] = function () {
    $libSodiumWrapper = new \Coursework\LibSodiumWrapper();
    return $libSodiumWrapper;
};

$container['sessionWrapper'] = function () {
    $sessionWrapper = new \Coursework\SessionWrapper();
    return $sessionWrapper;
};

$container['sessionModel'] = function () {
    $sessionModel = new \Coursework\SessionModel();
    return $sessionModel;
};

$container['messageModel'] = function ($container) {
    $messageModel = new \Coursework\MessageModel(
        $container['soapWrapper'],
        $container['settings']['soap']['login'],
        $container['telemetryLogger']
    );
    return $messageModel;
};

$container['doctrineSqlQueries'] = function () {
    $doctrineSqlQueries = new \Coursework\DoctrineSqlQueries();
    return $doctrineSqlQueries;
};

$container['soapWrapper'] = function ($container) {
    $soapWrapper = new \Coursework\SoapWrapper($container['settings']['soap']['connection']);
    return $soapWrapper;
};

$container['xmlParser'] = function ($container) {
    $xmlParser = new \Coursework\XmlParser();
    return $xmlParser;
};

