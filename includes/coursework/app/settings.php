<?php

/**
 * settings.php Script
 *
 * Script that configures the application's settings
 *
 * @author Jakub Chamera
 * Date: 17/11/2021
 */

ini_set('display_errors', 'On');
ini_set('html_errors', 'On');
ini_set('xdebug.trace_output_name', 'AA_coursework.%t');

$app_url = dirname($_SERVER['SCRIPT_NAME']);
$css_path = $app_url . '/css/coursework_css.css';
$log_file_path = '../logs';

define('CSS_PATH', $css_path);
define('LOG_FILE_PATH', $log_file_path);
define('APP_NAME', 'Telemetry Data Processing Coursework');

define ('BCRYPT_ALGO', PASSWORD_DEFAULT);
define ('BCRYPT_COST', 12);

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
//    //SETTINGS BELOW USED FOR LIVE PROD
//    'doctrine_settings' => [
//        'driver' => 'pdo_mysql',
//        'host' => 'mysql.tech.dmu.ac.uk',
//        'dbname' => 'p2409490db',
//        'port' => '3306',
//        'user' => 'p2409490_web',
//        'password' => 'rilEd+17',
//        'charset' => 'utf8mb4'
//    ],
    //SETTINGS BELOW USED FOR LOCAL DEV
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

