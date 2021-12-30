<?php

/**
 * settings.php Script
 *
 * Script that configures the application's settings
 *
 * @author Jakub Chamera
 * Date: 17/11/2021
 */

//ini_set('display_errors', 'On');
//ini_set('html_errors', 'On');
//ini_set('xdebug.trace_output_name', 'session_example.%t');

$app_url = dirname($_SERVER['SCRIPT_NAME']);
$css_path = $app_url . '/css/coursework_css.css';
$log_file_path = '/p3t/phpappfolder/logs/';

define('CSS_PATH', $css_path);
define('LOG_FILE_PATH', $log_file_path);
define('APP_NAME', 'Telemetry Data Processing Coursework');

$settings = [
    "settings" => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'mode' => 'development',
        'debug' => true,
        'view' => [
            'template_path' => __DIR__ . '/templates/',
            'twig' => [
                'cache' => false,
                'auto_reload' => true,
            ]],
        'pdo_settings' => [
            'rdbms' => 'mysql',
            'host' => 'localhost',
            'db_name' => 'session_db',
            'port' => '3306',
            'user_name' => 'session_user',
            'user_password' => 'session_user_pass',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => true,
            ],
        ],
        'soap' => [
            'connection' => [
                'wsdl' => 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl',
                'options' => [
                    'trace' => true,
                    'exceptions' => true
                ]
            ],
            'login' => [
                'username' => '21_2409490',
                'password' => 'M2mJSM2021swad!'
            ]
        ],
    ],
    'doctrine_settings' => [
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'dbname' => 'telemetry_data_db',
        'port' => '3306',
        'user' => 'developer',
        'password' => 'password',
        'charset' => 'utf8mb4'
    ],

];

return $settings;
