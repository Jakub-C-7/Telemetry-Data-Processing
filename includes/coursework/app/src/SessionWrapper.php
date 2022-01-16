<?php

/**
 * Class SessionWrapper creates a wrapper for the SESSION global array.
 *
 * @author Jakub Chamera
 * @package Coursework
 */

namespace Coursework;

class SessionWrapper implements SessionInterface
{
    /**
     * @var null Instance of the session logger for logging session activity.
     */
    private $session_logger;

    /**
     * SessionWrapper constructor. Creation nullifies the sesson logger.
     */
    public function __construct() {

        $this->session_logger = null;
    }

    public function __destruct() {
    }

    /**
     * Sets the session variable
     * @param $session_key String The session key.
     * @param $session_value_to_set String The new session key
     * @return bool True if set successfully, false if unsuccessful.
     */
    public function setSessionVar($session_key, $session_value_to_set)
    {
        $session_value_set_successfully = false;
        if (!empty($session_value_to_set))
        {
            $_SESSION[$session_key] = $session_value_to_set;
            if (strcmp($_SESSION[$session_key], $session_value_to_set) == 0)
            {
                $session_value_set_successfully = true;
            }
        }
        return $session_value_set_successfully;
    }

    /**
     * Gets the session variable.
     * @param $session_key String The session key
     * @return false|mixed False if unsuccessful, string of session key if it exists.
     */
    public function getSessionVar($session_key)
    {
        $session_value = false;

        if (isset($_SESSION[$session_key]))
        {
            $session_value = $_SESSION[$session_key];
        }
        return $session_value;
    }

    /**
     * Function for unsetting the session key.
     * @param $session_key String The session key string.
     * @return bool Returns false if unsuccessful or true if successful.
     */
    public function unsetSessionVar($session_key)
    {
        $unset_session = false;
        if (isset($_SESSION[$session_key]))
        {
            unset($_SESSION[$session_key]);
        }
        if (!isset($_SESSION[$session_key]))
        {
            $unset_session = true;
        }
        return $unset_session;
    }

    /**
     * Function for setting the session logger.
     * @param $session_logger String The session logger.
     */
    public function setLogger($session_logger)
    {
        $this->session_logger = $session_logger;
    }
}

