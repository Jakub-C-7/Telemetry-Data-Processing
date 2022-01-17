<?php

/**
 * Class BcryptWrapper Wrapper class for the PHP BCrypt library.
 *
 * Used for hashing passwords before they get stored in the database. This is done to avoid sensitive data storage in
 * plain text.
 *
 * @author Jakub Chamera
 * @package Coursework
 * Date: 08/01/2022
 */

namespace Coursework;

class BcryptWrapper
{

    /**
     * Function for hashing a password string.
     * @param $string_to_hash -Password string to be hashed.
     * @return false|string|null Returns the hashed password on success, null if the string is empty, and false if
     * hashing fails.
     */
  public function createHashedPassword($string_to_hash)
  {
    $password_to_hash = $string_to_hash;
    $bcrypt_hashed_password = '';

    if (!empty($password_to_hash))
    {
      $options = array('cost' => BCRYPT_COST);
      $bcrypt_hashed_password = password_hash($password_to_hash, BCRYPT_ALGO, $options);
    }
    return $bcrypt_hashed_password;
  }

    /**
     * Function for checking the authenticity of a password string against a hashed password to see if they match.
     * @param $string_to_check -The input password string to be checked.
     * @param $stored_user_password_hash -The stored password hash to be checked against.
     * @return bool Returns true if the passwords match and authentication succeeds, returns false if it fails.
     */
  public function authenticatePassword($string_to_check, $stored_user_password_hash)
  {
    $user_authenticated = false;
    $current_user_password = $string_to_check;
    $stored_user_password_hash = $stored_user_password_hash;
    if (!empty($current_user_password) && !empty($stored_user_password_hash))
    {
      if (password_verify($current_user_password, $stored_user_password_hash))
      {
        $user_authenticated = true;
      }
    }
    return $user_authenticated;
  }
}

