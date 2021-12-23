<?php
/**
 * Validator Class
 *
 */
namespace Coursework;

class Validator
{
    public function __construct() { }

    public function __destruct() { }

    public function sanitiseString(string $string_to_sanitise): string
    {
        $sanitised_string = false;

        if (!empty($string_to_sanitise))
        {
            $sanitised_string = filter_var($string_to_sanitise, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return $sanitised_string;
    }


    public function sanitiseBoolean(bool $boolean_to_sanitise): bool
    {
        $sanitised_boolean = false;

        if (!empty($boolean_to_sanitise))
        {
            $sanitised_boolean = filter_var($boolean_to_sanitise, FILTER_VALIDATE_BOOLEAN);
        }
        return $sanitised_boolean;
    }

}