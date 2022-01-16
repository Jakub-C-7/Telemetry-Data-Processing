<?php

/**
 * submitregistration.php script takes user inputs and creates a new account.
 *
 * @author Jakub Chamera
 * Date: 07/01/2022
 */

use Doctrine\DBAL\DriverManager;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/submitregistration', function (Request $request, Response $response) use ($app) {

    session_start();

    $logger = $app->getContainer()->get('telemetryLogger');

    $errors = [];

    if(isset($_SESSION['user'])) {
        $response = $response->withRedirect("index.php");
        $logger->error('The user: '. $_SESSION['user']. ' attempted to enter the submitregistration page but was already 
        logged in');
        return $response;

    } else {
        $validator = $this->get('validator');
        $database_connection_settings = $app->getContainer()->get('doctrine_settings');
        $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
        $database_connection = DriverManager::getConnection($database_connection_settings);
        $queryBuilder = $database_connection->createQueryBuilder();

        $userDetails = $request->getParsedBody();

        $validatedUserDetails = validateUserDetails($userDetails, $validator);

        $exists = $doctrine_queries::checkUserExists($queryBuilder, $validatedUserDetails['email']);

        if (!$exists) {
            if ($validatedUserDetails['email'] !== null && $validatedUserDetails['password'] !== null &&
                $validatedUserDetails['confirmPassword'] !== null && $validatedUserDetails['phoneNumber'] !== null) {

                $logger->info('Validation has passed for the user: ' . $validatedUserDetails['email']);

                $validatedUserDetails['password'] = hash_password($app, $validatedUserDetails['password']);

                $storage_result = storeNewUser($app, $validatedUserDetails);

                if ($storage_result == false) {
                    $logger->info($validatedUserDetails['email'] . ' has just registered');
                } else {
                    $logger->error($validatedUserDetails['email'] . ' has just failed to be registered');
                }
            } else {
                $logger->error('Validation not passed for the user.' . $validatedUserDetails['email']);
            }
        } else {
            $logger->error('Account creation failed. User with the following email: ' .
                $validatedUserDetails['email']. ' already exists');
            $errors['exists'] = 'There is already an account with that email address';
        }
        //ENCRYPT ALL DETAILS
//    $encrypted = encrypt($app, $validatedUserDetails);

        //ENCODE ALL ENCRYPTED DETAILS
//    $encoded = encode($app, $encrypted);

        //USED FOR DECRYPTION OF DETAILS
//    $decrypted = decrypt($app, $encoded);

        $errors = array_merge($errors, $validator->getErrors());
        if (empty($errors)) {
            $storage_result = 'Success!';
            $logger->info('A user entered the submitregistration page. No errors in user inputs.');
            return $this->view->render($response,
                'submitregistration.html.twig',
                [
                    'Css_path' => CSS_PATH,
                    'landing_page' => $_SERVER["SCRIPT_NAME"],
                    'initial_input_box_value' => null,
                    'page_title' => APP_NAME,
                    'page_heading_1' => 'Registration',
                    'method' => 'post',
                    'email' => $userDetails['email'],
                    'phoneNumber' => $userDetails['phoneNumber'],
                    'storage_result' => $storage_result,
                ]);
        } else {
            $logger->info('A user attempted to register but there were errors');
            return $this->view->render($response,
                'registration.html.twig',
                [
                    'Css_path' => CSS_PATH,
                    'landing_page' => $_SERVER["SCRIPT_NAME"],
                    'initial_input_box_value' => null,
                    'page_title' => APP_NAME,
                    'page_heading_1' => 'Registration',
                    'action' => 'submitregistration',
                    'method' => 'post',
                    'errors'=> $errors
                ]);
        }
    }

})->setName('submitregistration');

/**
 * Function for validating user inputs for registering a new account.
 * @param array $userDetails The array containing user details to be validated.
 * @param \Coursework\Validator $validator The instance of the Validator class being used for validation.
 * @return array Returns ana array containing sanitised and validated data.
 */
function validateUserDetails(array $userDetails, \Coursework\Validator $validator): array
{
    $validatedUserDetails = [];

    if (isset($userDetails['email']) && $validator->validateEmail($userDetails['email']) !== false) {
        $validatedUserDetails['email'] = $userDetails['email'];
    } else {
        $validatedUserDetails['email'] = null;
    }

    if (isset($userDetails['password']) && $validator->validatePassword($userDetails['password']) !== false) {
        $validatedUserDetails['password'] = $userDetails['password'];
    } else {
        $validatedUserDetails['password'] = null;
    }

    if (isset($userDetails['confirmPassword']) && $validator->validateConfirmPassword($userDetails['confirmPassword'],
            $userDetails['password']) !== false) {
        $validatedUserDetails['confirmPassword'] = $userDetails['confirmPassword'];
    } else {
        $validatedUserDetails['confirmPassword'] = null;
    }

    if (isset($userDetails['phoneNumber']) && $validator->validatePhoneNumber($userDetails['phoneNumber'],
            'sender') !== false) {
        $validatedUserDetails['phoneNumber'] = $userDetails['phoneNumber'];
    } else {
        $validatedUserDetails['phoneNumber'] = null;
    }

    return $validatedUserDetails;
}

/**
 * Function for storing (registering) new users in the database.
 * @param $app -Instance of the app used to inject dependencies.
 * @param $userDetails -User details being stored in the database.
 * @return false Returns false if everything has gone well and registration has suceeded.
 * @throws \Doctrine\DBAL\Exception Throws a doctrine exception if an error occurs.
 */
function storeNewUser($app, $userDetails)
{
    $logger = $app->getContainer()->get('telemetryLogger');

    $database_connection_settings = $app->getContainer()->get('doctrine_settings');
    $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
    $database_connection = DriverManager::getConnection($database_connection_settings);
    $queryBuilder = $database_connection->createQueryBuilder();

    $exists = $doctrine_queries::checkUserExists($queryBuilder, $userDetails['email']);

    if ($exists == false) {
        $queryBuilder = $database_connection->createQueryBuilder();
        $userRegistrationResult = $doctrine_queries::insertUser($queryBuilder, $userDetails);

        if ($userRegistrationResult['outcome'] == 1) {
            $logger->info('User was successfully stored using the query '.$userRegistrationResult['sql_query']);
        } else {
            $logger->error('There was a problem when storing the user '. $userDetails['email']);

        }
    } else {
        $logger->info('User '. $userDetails['email'].' already exists and has not been stored.');
    }

    return false;
}

/**
 * Uses the Bcrypt library with constants from settings.php to create hashes of the entered password
 * @param $app -Instance of the app being used to inject dependencies.
 * @param $password_to_hash -The password string being hashed.
 * @return string Returns the password string in its hashed form.
 */
function hash_password($app, $password_to_hash): string
{
    $bcrypt_wrapper = $app->getContainer()->get('bcryptWrapper');
    $hashed_password = $bcrypt_wrapper->createHashedPassword($password_to_hash);
    return $hashed_password;
}

/**
 * Function for encrypting data using libSodium.
 * @param $app -Instance of the app.
 * @param $cleaned_parameters -The cleaned input credentials.
 * @return array Returns an array of encrypted data.
 */
function encrypt($app, $cleaned_parameters)
{
    $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');

    $encrypted = [];
    $encrypted['encrypted_email_and_nonce'] = $libsodium_wrapper->encrypt($cleaned_parameters['email']);
    $encrypted['encrypted_phone_number_and_nonce'] = $libsodium_wrapper->encrypt($cleaned_parameters['phoneNumber']);

    return $encrypted;
}

/**
 * Function for encoding encrypted data.
 * @param $app -Instance of the app.
 * @param $encrypted_data - The encrypted data/credentials.
 * @return array Returns an array of encoded data.
 */
function encode($app, $encrypted_data)
{
    $base64_wrapper = $app->getContainer()->get('base64Wrapper');

    $encoded = [];

    $encoded['encoded_email'] = $base64_wrapper->encode_base64($encrypted_data['encrypted_email_and_nonce']['nonce_and_encrypted_string']);
    $encoded['encoded_phone_number'] = $base64_wrapper->encode_base64($encrypted_data['encrypted_phone_number_and_nonce']['nonce_and_encrypted_string']);
    return $encoded;
}

/**
 * Function both decodes base64 then decrypts the extracted cipher code
 * @param $libsodium_wrapper
 * @param $base64_wrapper
 * @param $encoded
 * @return array
 */
function decrypt($app, $encoded): array
{
    $decrypted_values = [];
    $base64_wrapper = $app->getContainer()->get('base64Wrapper');
    $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');

    $decrypted_values['email'] = $libsodium_wrapper->decrypt(
        $base64_wrapper,
        $encoded['encoded_email']
    );

    $decrypted_values['phone_number'] = $libsodium_wrapper->decrypt(
        $base64_wrapper,
        $encoded['encoded_phone_number']
    );

    return $decrypted_values;
}

