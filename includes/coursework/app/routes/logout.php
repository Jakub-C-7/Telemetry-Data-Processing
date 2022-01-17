<?php

/**
 * logout.php script enables users to logout.
 *
 * Destroys a session if it exists and redirects user to the starting menu page.
 *
 * @author Jakub Chamera
 * Date: 10/01/2022
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/logout', function (Request $request, Response $response) use ($app) {

    $logger = $app->getContainer()->get('telemetryLogger');
    $logger->error('The user: '. $_SESSION['user']. ' logged out');

    $sessionModel = $this->get('sessionModel');
    $sessionModel->logout();

    $response = $response->withRedirect("startingmenu");

    return $response;

})->setName('logout');

