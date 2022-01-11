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
    private $server_type;
    private $password;
    private $storage_result;
    private $session_wrapper_file;
    private $session_wrapper_database;
    private $database_connection_settings;
    private $sql_queries;

    public function __construct()
    {
        $this->username = null;
        $this->server_type = null;
        $this->password = null;
        $this->storage_result = null;
        $this->session_wrapper_file = null;
        $this->session_wrapper_database = null;
        $this->database_connection_settings = null;
        $this->sql_queries = null;
    }

    public function __destruct() { }

    public function setSessionUsername($username)
    {
        $this->username = $username;
    }

    public function setSessionPassword($password)
    {
        $this->password = $password;
    }

    public function setServerType($server_type)
    {
        $this->server_type = $server_type;
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

    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
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

    public function getStoredSession($session_key)
    {


    }

    private function storeDataInSessionFile()
    {
        $store_result = false;
        $store_result_username = $this->session_wrapper_file->setSessionVar('user_name', $this->username);
        $store_result_password = $this->session_wrapper_file->setSessionVar('password', $this->password);

        if ($store_result_username !== false && $store_result_password !== false){
            $store_result = true;
        }
        return $store_result;
    }

    public function storeDataInSessionDatabase()
    {
        $store_result = false;

        $this->session_wrapper_database->setSqlQueries( $this->sql_queries);
        $this->session_wrapper_database->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->session_wrapper_database->makeDatabaseConnection();

        $store_result_username = $this->session_wrapper_database->setSessionVar('user_name', $this->username);
        $store_result_password = $this->session_wrapper_database->setSessionVar('user_password', $this->password);

        if ($store_result_username !== false && $store_result_password !== false)
        {
            $store_result = true;
        }
        return $store_result;
    }

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

    private function retrieveStoredValuesFromSession()
    {
        $retrieved_values = [];
        $retrieved_values['username'] = $this->session_wrapper_file->getSessionVar('user_name');
        $retrieved_values['password'] = $this->session_wrapper_file->getSessionVar('password');
        return $retrieved_values;
    }

    private function retrieveStoredValuesFromDatabase()
    {
        $retrieved_values = [];
        $this->session_wrapper_database->setSqlQueries( $this->sql_queries);
        $this->session_wrapper_database->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->session_wrapper_database->SetLogger($this->session_logger);
        $this->session_wrapper_database->makeDatabaseConnection();

        $retrieved_values['username'] = $this->session_wrapper_database->getSessionVar('user_name');
        $retrieved_values['password'] = $this->session_wrapper_database->getSessionVar('user_password');

        return $retrieved_values;
    }

    public function setLogger($session_logger)
    {
        $this->session_logger = $session_logger;
    }

    public function login($session_logger)
    {
        $this->session_logger = $session_logger;
    }
}
