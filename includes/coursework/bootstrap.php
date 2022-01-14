<?php

/**
 * Bootstrap File creates the app and configures it.
 *
 * Configures the app by calling the dependencies, and settings files.
 *
 * @author : Jakub Chamera
 * Date: 17/11/2021
 */

require 'vendor/autoload.php';

$app_path = __DIR__ . '/app/';

$settings = require $app_path . 'settings.php';

$makeXdebugTraces = true;

if ($makeXdebugTraces && function_exists('xdebug_start_trace'))
{
    xdebug_start_trace();
}

$container = new \Slim\Container($settings);

require $app_path . 'dependencies.php';

$app = new \Slim\App($container);

require $app_path . 'routes.php';

$app->run();

if ($makeXdebugTraces && function_exists('xdebug_stop_trace'))
{
    xdebug_stop_trace();
}

