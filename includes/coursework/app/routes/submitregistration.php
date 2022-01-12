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

    $errors = "";

    if(isset($_SESSION['user'])) {
        $response = $response->withRedirect("/coursework_public/");
        return $response;

    } else {
        $validator = $this->get('validator');
        $logger = $app->getContainer()->get('telemetryLogger');
        $database_connection_settings = $app->getContainer()->get('doctrine_settings_dev');
        $doctrine_queries = $app->getContainer()->get('doctrineSqlQueries');
        $database_connection = DriverManager::getConnection($database_connection_settings);
        $queryBuilder = $database_connection->createQueryBuilder();

        $userDetails = $request->getParsedBody();

        $validatedUserDetails = validateUserDetails($userDetails, $validator);

        $exists = $doctrine_queries::checkUserExists($queryBuilder, $validatedUserDetails['email']);

        if (!$exists) {
            if ($validatedUserDetails['email'] !== null && $validatedUserDetails['password'] !== null &&
                $validatedUserDetails['phoneNumber'] !== null) {
                $logger->info('Validation has been passed for the user' . $validatedUserDetails['email']);

                //Hash password
                $validatedUserDetails['password'] = hash_password($app, $validatedUserDetails['password']);

                //Store the new user
                $storage_result = storeNewUser($app, $validatedUserDetails);

                if ($storage_result == false) {
                    $storage_result = 'Registration Success!';
                    $logger->info($validatedUserDetails['email'] . ' has just registered');
                } else {
                    $storage_result = 'Registration has failed!';
                    $logger->error($validatedUserDetails['email'] . ' has just failed to be registered');
                }
            } else {
                $logger->error('Validation not passed for the user.' . $validatedUserDetails['email']);
                $errors = $errors . 'Entered data did not pass validation, please try again with valid data.';
            }
        } else {
            $logger->error('Account creation failed. User with the following email already exists.' .
                $validatedUserDetails['email']);
            $storage_result = 'Registration has failed!. USER ALREADY EXISTS';
            $errors = $errors . 'ERROR: User with that email already exists. ';
        }

        //ENCRYPT ALL DETAILS
//    $encrypted = encrypt($app, $validatedUserDetails);

        //ENCODE ALL ENCRYPTED DETAILS
//    $encoded = encode($app, $encrypted);

        //USED FOR DECRYPTION OF DETAILS
//    $decrypted = decrypt($app, $encoded);

        if ($errors == "") {
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
                    'password' => $userDetails['password'],
                    'storage_result' => $storage_result,
                ]);
        } else {
            //TODO: Include validation errors next to the respective fields.
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
                    'errors' => $errors
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

    $database_connection_settings = $app->getContainer()->get('doctrine_settings_dev');
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

function encrypt($app, $cleaned_parameters)
{
    $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');

    $encrypted = [];
    $encrypted['encrypted_email_and_nonce'] = $libsodium_wrapper->encrypt($cleaned_parameters['email']);
    $encrypted['encrypted_phone_number_and_nonce'] = $libsodium_wrapper->encrypt($cleaned_parameters['phoneNumber']);

    return $encrypted;
}

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

