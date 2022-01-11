<?php

namespace Coursework;

/**
 * Interface SessionInterface
 *
 * @author Jakub Chamera
 * @package Coursework
 */
interface SessionInterface
{
    /**
     * Assigns a session value to storage.  Session key is used to identify the value
     *
     * @param $session_key
     * @param $session_value_to_set
     * @return mixed
     */
    public function setSessionVar($session_key, $session_value_to_set);

    /**
     * Returns the value frm storage associated with the given key
     *
     * @param $session_key
     * @return mixed
     */
    public function getSessionVar($session_key);

    /**
     * Remove the stored value from storage
     *
     * @param $session_key
     * @return mixed
     */
    public function unsetSessionVar($session_key);

    /**
     * Log all relevant activity
     *
     * @return mixed
     */
    public function setLogger($sessionLogger);

}