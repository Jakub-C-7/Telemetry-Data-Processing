<?php

/**
 * settings.php script configures the application's settings.
 *
 * Defines settings such as connection details, bcrypt algorithm configuration, file paths, and php.ini settings.
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
                'username' => '', // ENTER OWN USERNAME AND PASSWORD FOR EE M2M
                'password' => ''
            ]
        ],
    ],
//    //SETTINGS BELOW USED FOR LIVE PROD
//    'doctrine_settings' => [
//        'driver' => 'pdo_mysql',
//        'host' => 'mysql.tech.dmu.ac.uk',
//        'dbname' => '',
//        'port' => '3306',
//        'user' => '', //ENTER OWN USERNAME, PASSWORD, AND DBNAME FOR DATABASE
//        'password' => '',
//        'charset' => 'utf8mb4'
//    ],
    //SETTINGS BELOW USED FOR LOCAL DEV
    'doctrine_settings' => [
        'driver' => 'pdo_mysql',
        'host' => '',
        'dbname' => '',
        'port' => '',
        'user' => '',
        'password' => '',
        'charset' => 'utf8mb4'
    ],
];

return $settings;

