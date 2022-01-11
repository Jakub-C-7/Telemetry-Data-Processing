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

    session_start();

    if(isset($_SESSION['user'])) {
        $response = $response->withRedirect("index.php");
        return $response;

    } else {

        $validator = $this->get('validator');
        $logger = $app->getContainer()->get('telemetryLogger');

        //GET PARSED DATA
        $enteredLoginDetails = $request->getParsedBody();

        //GET STORED LOGIN DETAILS FOR THE ENTERED EMAIL
        $retrievedLoginDetails = retrieveStoredLoginCredentials($app, $enteredLoginDetails['email']);

        //CHECK LOGIN DETAILS AGAINST EACH OTHER
        if (checkLoginCredentials($app, $enteredLoginDetails, $retrievedLoginDetails)) {
            $logger->info($retrievedLoginDetails['email'] . ' has just logged in.');

            $_SESSION['user'] = $retrievedLoginDetails['email'];

            $response = $response->withRedirect("/coursework_public/");
            return $response;

        } else {
            $error = 'The entered details are invalid.';
            $logger->error($enteredLoginDetails['email'] .
                ' has just attempted to log-in and failed. Credentials did not match.');
            createLoggedInErrorView($app, $response, $error);
            //TODO: Display an error and allow the users to re-try login and ensure that they cant enter the website yet.
        }
    }


})->setName('submitlogin');

/**
 * Function for checking two login credentials against each other to assert if they're the same.
 * @param $app -The app used to inject bcrypt.
 * @param $enteredCredentials -The details entered by the user that need to be authenticated.
 * @param $storedCredentials -The stored credentials with the same email.
 * @return mixed|null Returns the result of password authentication. True if successful and false if not.
 */
function checkLoginCredentials($app, $enteredCredentials, $storedCredentials)
{
    $result = false;
    $bcryptWrapper = $app->getContainer()->get('bcryptWrapper');

    if ($storedCredentials != null) {
        if ($enteredCredentials['email'] == $storedCredentials['email']) {
            $result = $bcryptWrapper->authenticatePassword($enteredCredentials['password'],
                $storedCredentials['password']);
        }
    }
    return $result;
}

/**
 * Function retrieves stored login credentials by email. Retrieves email and password.
 * @param $app -The app used to inject doctrine.
 * @param $email -The email of the account that the credentials are being retrieved for.
 * @return mixed Returns the results of the retrieval request.
 * @throws \Doctrine\DBAL\Exception - Throws an exception if the retrieval fails.
 */
function retrieveStoredLoginCredentials($app, $email)
{
    $logger = $app->getContainer()->get('telemetryLogger');
    $database_connection_settings = $app->getContainer()->get('doctrine_settings');
    $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
    $database_connection = DriverManager::getConnection($database_connection_settings);
    $queryBuilder = $database_connection->createQueryBuilder();

    $retrievalResult = $doctrine_queries->getUserLoginCredentials($queryBuilder, $email);

    if (!empty($retrievalResult['result']) && $retrievalResult['outcome'] != false) {
        $logger->info('The login credentials were successfully authenticated using the query: '.$retrievalResult['sql_query']);
        $result = $retrievalResult['result'][0];
        return $result;
    } else {
        $logger->error('Error while authenticating login credentials using query: '.$retrievalResult['sql_query']);
        return false;
    }
}

/**
 * Function for creating the error view page if login fails.
 * @param $app - The app parameter used to inject dependencies.
 * @param $response -The response error page being returned.
 */
function createLoggedInErrorView($app, $response, $error) {
    $view = $app->getContainer()->get('view');
    $view->render($response,
        'notloggedinerrorpage.html.twig',
        [
            'Css_path' => CSS_PATH,
            'landing_page' => $_SERVER["SCRIPT_NAME"],
            'page_title' => APP_NAME,
            'page_heading_1' => 'Login Failed',
            'message' => 'Oops, something went wrong while logging in. ' .$error
        ]
    );
}

/**
 * Function that creates a new session for a user and assigns their login email to it.
 * @param $app
 * @param $userDetails
 * @return mixed
 */
function createUserSession($app, $userDetails)
{
    $session_wrapper = $app->getContainer()->get('sessionWrapper');
    $session_model = $app->getContainer()->get('sessionModel');

    $store_result = '';
    $session_model->setSessionUsername($userDetails['email']);
    $session_model->setSessionWrapperFile($session_wrapper);
    $session_model->storeData();

    $store_result = $session_model->getStorageResult();

    return $store_result;
}

