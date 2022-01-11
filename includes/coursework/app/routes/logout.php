<?php

/**
 * logout.php script enables users to logout.
 *
 * Destroys a session if it exists and redirects to the starting menu page.
 *
 * @author Jakub Chamera
 * Date: 10/01/2022
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/logout', function (Request $request, Response $response) use ($app) {

    session_start();

    session_destroy();

    $response = $response->withRedirect("/coursework_public/startingmenu");

    return $response;

})->setName('logout');


function endUserSession(): void
{
    //point to the session being destroyed

    session_unset();
    session_destroy();
    session_start();

}

