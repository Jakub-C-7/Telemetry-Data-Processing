<?php

namespace Coursework;

/**
 * Class SessionWrapper creates a wrapper for the SESSION global array.
 *
 * @author Jakub Chamera
 * @package Coursework
 */
class SessionWrapper implements SessionInterface
{
    private $session_logger;

    public function __construct() {

        $this->session_logger = null;
    }

    public function __destruct() {
    }

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

    public function getSessionVar($session_key)
    {
        $session_value = false;

        if (isset($_SESSION[$session_key]))
        {
            $session_value = $_SESSION[$session_key];
        }
        return $session_value;
    }

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

    public function setLogger($session_logger)
    {
        $this->session_logger = $session_logger;
    }
}
