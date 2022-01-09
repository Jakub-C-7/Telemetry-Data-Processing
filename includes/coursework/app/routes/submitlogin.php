<?php

/**
 * submitlogin.php script checks entered details and attempts to log a user in.
 *
 * @author Jakub Chamera
 * Date: 08/01/2022
 */

use Doctrine\DBAL\DriverManager;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/submitlogin', function (Request $request, Response $response) use ($app) {

    $validator = $this->get('validator');
    $logger = $app->getContainer()->get('telemetryLogger');

    //GET PARSED DATA
    $enteredLoginDetails = $request->getParsedBody();

    //GET STORED LOGIN DETAILS FOR THE ENTERED EMAIL
    $retrievedLoginDetails = retrieveStoredLoginCredentials($app, $enteredLoginDetails['email']);

    //CHECK LOGIN DETAILS AGAINST EACH OTHER
    if (checkLoginCredentials($app, $enteredLoginDetails, $retrievedLoginDetails)) {
        echo 'This has worked! Your details match!';
        $logger->info($retrievedLoginDetails['email']. ' has just logged in');
        //TODO: Allow the users into the website, create a new session.
    } else {
        echo 'The details are incorrect';
        $logger->error($retrievedLoginDetails['email'].
            ' has just attempted to log-in and failed. Credentials did notmatch');
        //TODO: Allow the users to re-try login and ensure that they cant enter the website yet.
    }

    return $this->view->render($response,
        'submitlogin.html.twig',
        [
            'Css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'initial_input_box_value' => null,
            'page_title' => APP_NAME,
            'page_heading_1' => 'Log-in',
            'method' => 'post'
        ]);

})->setName('submitlogin');

/**
 * Function for checking two login credentials against each other to assert if they're the same.
 * @param $app -The app used to inject bcrypt.
 * @param $enteredCredentials -The details entered by the user that need to be authenticated.
 * @param $storedCredentials -The stored credentials with the same email.
 * @return mixed|null Returns the result of password authentication. True if successful and null if not.
 */
function checkLoginCredentials($app, $enteredCredentials, $storedCredentials)
{
    $result = null;
    $bcryptWrapper = $app->getContainer()->get('bcryptWrapper');

    if ($enteredCredentials['email'] == $storedCredentials['email']){
        $result = $bcryptWrapper->authenticatePassword($enteredCredentials['password'], $storedCredentials['password']);
    }
    return $result;
}

/**
 * Function retrieves stored login credentials by email. Retrieves email and password.
 * @param $app -The app used to inject doctrine.
 * @param $email -The email of the account that the credentials are being retrieved for.
 * @return mixed Returns the results of the retrieval request.
 */
function retrieveStoredLoginCredentials($app, $email)
{
    $database_connection_settings = $app->getContainer()->get('doctrine_settings');
    $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
    $database_connection = DriverManager::getConnection($database_connection_settings);
    $queryBuilder = $database_connection->createQueryBuilder();

    $retrievalResult = $doctrine_queries->getUserLoginCredentials($queryBuilder, $email);

    $result = $retrievalResult['result'][0];

    return $result;
}


