<?php
/**
 * Bootstrap File
 *
 * Boostrap creates the app and configures it by calling the dependencies, and settings files.
 *
 * Author: Jakub Chamera
 * Date: 17/11/2021
 */

require 'vendor/autoload.php';

$app_path = __DIR__ . '/app/';

$settings = require $app_path . 'settings.php';

$container = new \Slim\Container($settings);

require $app_path . 'dependencies.php';

$app = new \Slim\App($container);

require $app_path . 'routes.php';

$app->run();
