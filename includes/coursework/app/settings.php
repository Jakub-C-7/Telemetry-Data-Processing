<?php
/**
 * Settings File
 * Script that configures the application's settings
 *
 * Author: Jakub Chamera
 * Date: 17/11/2021
 */

//ini_set('display_errors', 'On');
//ini_set('html_errors', 'On');
//ini_set('xdebug.trace_output_name', 'session_example.%t');

$app_url = dirname($_SERVER['SCRIPT_NAME']);
$css_path = $app_url . '/css/coursework_css.css';
$log_file_path = '/p3t/phpappfolder/logs/';
$all_messages = 'all-messages';

define('CSS_PATH', $css_path);
define('LOG_FILE_PATH', $log_file_path);
define('ALL_MESSAGES', $all_messages);
define('APP_NAME', 'Telemetry Data Processing Coursework');

/* M2M service configurations */
//define('M2M_MSISDN', );
//define('M2M_USERNAME', '');
//define('M2M_PASSWORD', '');
//$wsdl = 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl';
//define('WSDL', $wsdl);

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
        ]
    ],

];

return $settings;
