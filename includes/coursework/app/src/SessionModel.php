<?php

/**
 * Class SessionModel stores validated values in a session.
 *
 * @author Jakub Chamera
 * @package Coursework
 */

namespace Coursework;

class SessionModel
{
    private $username;
    private $storage_result;
    private $session_wrapper_file;
    private $session_wrapper_database;
    private $database_connection_settings;

    public function __construct()
    {
        $this->username = null;
        $this->storage_result = null;
        $this->session_wrapper_file = null;
        $this->session_wrapper_database = null;
        $this->database_connection_settings = null;
    }

    public function setSessionUsername($username)
    {
        $this->username = $username;
    }

    public function setSessionWrapperFile($session_wrapper)
    {
        $this->session_wrapper_file = $session_wrapper;
    }

    public function setSessionWrapperDatabase($session_wrapper)
    {
        $this->session_wrapper_database = $session_wrapper;
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function storeData()
    {
        switch ($this->server_type)
        {
            case 'database':
                $storage_result = $this->storeDataInSessionDatabase();
                break;
            case 'file':
            default:
                $storage_result = $this->storeDataInSessionFile();
        }
        $this->storage_result = $storage_result;
    }

    public function getStorageResult()
    {
        return $this->storage_result;
    }

    /**
     * Stores the data inside the session file
     * @return bool True if it has stored successfully, false if unsuccessful.
     */
    private function storeDataInSessionFile()
    {
        $store_result = false;
        $store_result_username = $this->session_wrapper_file->setSessionVar('user_name', $this->username);

        if ($store_result_username !== false){
            $store_result = true;
        }
        return $store_result;
    }

    /**
     * Stores session data in a database.
     * @return bool True if it has stored successfully, false if unsuccessful.
     */
    public function storeDataInSessionDatabase()
    {
        $store_result = false;

        $this->session_wrapper_database->setSqlQueries( $this->sql_queries);
        $this->session_wrapper_database->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->session_wrapper_database->makeDatabaseConnection();

        $store_result_username = $this->session_wrapper_database->setSessionVar('user_name', $this->username);

        if ($store_result_username !== false)
        {
            $store_result = true;
        }
        return $store_result;
    }

    /**
     * Retrieves values stored in the session.
     * @return array|string[] The array of values retrieved
     */
    public function retrieveStoredValues(): array
    {
        $retrieved_values = [];
        switch ($this->server_type)
        {
            case 'file':
                $retrieved_values = $this->retrieveStoredValuesFromSession();
                break;
            case 'database':
                $retrieved_values = $this->retrieveStoredValuesFromDatabase();
                break;
            default:
                $retrieved_values = [''];
        }
        return $retrieved_values;
    }

    /**
     * The values retrieved from the session
     * @return array The data in the session.
     */
    private function retrieveStoredValuesFromSession()
    {
        $retrieved_values = [];
        $retrieved_values['username'] = $this->session_wrapper_file->getSessionVar('user_name');
        return $retrieved_values;
    }

    /**
     * Retrieves session data from the database.
     * @return array The values retrived from the database.
     */
    private function retrieveStoredValuesFromDatabase()
    {
        $retrieved_values = [];
        $this->session_wrapper_database->setSqlQueries( $this->sql_queries);
        $this->session_wrapper_database->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->session_wrapper_database->SetLogger($this->session_logger);
        $this->session_wrapper_database->makeDatabaseConnection();

        $retrieved_values['username'] = $this->session_wrapper_database->getSessionVar('user_name');

        return $retrieved_values;
    }

    public function setLogger($session_logger)
    {
        $this->session_logger = $session_logger;
    }

    /**
     * Function for logging in users. Creates a new session, assigns the session user, regenerates the session ID.
     * @param $email -Email string of the user being logged in.
     */
    public function login($email)
    {
        session_start();

        $_SESSION['user'] = $email;

        session_regenerate_id();
    }

    /**
     * Function for logging users out. Destroys active session data.
     */
    public function logout()
    {
        session_start();

        session_destroy();
    }
}
