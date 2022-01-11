<?php

/**
 * Validator Class for validating message content and metadata.
 *
 * Message content validation includes temperature, fan, switch, and keypad and metadata validation includes datetime,
 * phone number, message ref, and bearer.
 *
 * Date: 01/01/2022
 */

namespace Coursework;

class Validator
{
    /**
     * @var array An array used to store validation errors.
     */
    private array $errors = [];

    /**
     * Get all errors stored in the errors array.
     * @return array An array containing all errors.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get one specific type of error stored in the errors array.
     * @param $errorName -Name of the error being retrieved. Error name is assigned by validation classes when an error
     * is encountered.
     * @return mixed Return the error string from the array.
     */
    public function getOneError($errorName)
    {
        return $this->errors[$errorName];
    }

    /**
     * Sanitises an input string using filter_var methods.
     * @param string $stringToSanitise String input to be sanitised.
     * @return string Return the sanitised string.
     */
    public function sanitiseString(string $stringToSanitise): string
    {
        $sanitisedString = false;

        if (!empty($stringToSanitise)) {
            $sanitisedString = filter_var($stringToSanitise, FILTER_SANITIZE_STRING,
                FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        return $sanitisedString;
    }

    /**
     * Sanitises and validates an email string input.
     * @param string $emailToSanitise Email string to be sanitised and validated.
     * @return string Return the sanitised and validated email string.
     */
    public function sanitiseEmail(string $emailToSanitise): string
    {
        $sanitisedEmail = false;

        if (!empty($emailToSanitise)) {
            $sanitisedEmail = filter_var($emailToSanitise, FILTER_SANITIZE_EMAIL);
            $sanitisedEmail = filter_var($sanitisedEmail, FILTER_VALIDATE_EMAIL);
        }

        return $sanitisedEmail;
    }

    /**
     * Tests to see if the datetime string isn't empty, is exactly equal to nineteen characters long, and if the
     * datetime is a valid and possible datetime to exist.
     * @param string $dateTimeToValidate Datetime to be validated.
     * @return bool Success or failure of validation (true or false).
     */
    public function validateDateTime(string $dateTimeToValidate) : bool
    {
        $valid = false;

        if (isset($dateTimeToValidate)) {
            if (!empty($dateTimeToValidate)) {
                if (strlen($dateTimeToValidate) == 19) {
                    // Sets the format to day/month/year hour:min:second to check the string is validated right
                    // Ex. 20/12/2021 01:40:56
                    $dateTime = \DateTime::createFromFormat('d/m/Y H:i:s', $dateTimeToValidate);
                    //Gets errors with the date time entered to avoid wrong dates that fit the format.
                    $errors = \DateTime::getLastErrors();
                    if (empty($errors['warning_count'])) {
                        $valid = true;
                    } else {
                        $this->errors['received'] = 'Invalid date';
                    }
                } else {
                    $this->errors['received'] = 'Invalid date';
                }
            } else {
                $this->errors['received'] = 'Empty date';
            }
        } else {
            $this->errors['received'] = 'Invalid date';
        }

        return $valid;
    }

    /**
     * Tests to see if the message ref string input isn't empty, and is numeric.
     * @param string $messageRefToValidate Message ref string to be validated.
     * @return bool Success or failure of validation (true or false).
     */
    public function validateMessageRef(string $messageRefToValidate): bool
    {
        $valid = false;

        if ($messageRefToValidate !== '') {
            if (is_numeric($messageRefToValidate)) {
                $valid = true;
            } else {
                $this->errors['ref'] = 'Non-numeric message ref';
            }
        } else {
            $this->errors['ref'] = 'Empty message ref';
        }

        return $valid;
    }

    /**
     * Tests to see if the phone number string input isn't empty, is numeric, is exactly 12 characters long, and
     * contains the british country code of '44' at the beginning.
     * @param string $phoneNumToValidate Phone number string to be validated.
     * @param string $role Role string of the phone number (usually sender or receiver).
     * @return bool Success or failure of validation (true or false).
     */
    public function validatePhoneNumber(string $phoneNumToValidate, string $role) : bool
    {
        $valid = false;

        if (isset($phoneNumToValidate)) {
            if (!empty($phoneNumToValidate)) {
                if (is_numeric($phoneNumToValidate)) {
                    if (strlen($phoneNumToValidate) == 12) {
                        if ($phoneNumToValidate[0] == '4' && $phoneNumToValidate[1] == '4') {
                            $valid = true;
                        } else {
                            $this->errors[$role] = 'Country code is not British';
                        }
                    } else {
                        $this->errors[$role] = 'Invalid phone number length';
                    }
                } else {
                    $this->errors[$role] = 'Non-numeric phone number';
                }
            } else {
                $this->errors[$role] = 'Empty phone number';
            }
        } else {
            $this->errors[$role] = 'Phone number is not set';
        }

        return $valid;
    }

    /**
     * Tests to see if the bearer string input matches one of the two available options 'sms' or 'gprs'. Accepts
     * uppercase, lowercase, or mixed-case inputs.
     * @param string $bearerToValidate Bearer string input to be validated.
     * @return bool Success or failure of validation (true or false).
     */
    public function validateBearer(string $bearerToValidate): bool
    {
        $valid = false;
        if ($bearerToValidate !== '') {
            if (strtolower($bearerToValidate) == 'sms' || strtolower($bearerToValidate) == 'gprs') {
                $valid = true;
            } else {
                $this->errors['bearer'] = 'Invalid bearer';
            }
        } else {
            $this->errors['bearer'] = 'Empty bearer value';
        }

        return $valid;
    }

    /**
     * Tests to see if a temperature string input isn't empty, is equal to or less than three characters long,
     * is numeric, and is within the range of -30 to 45.
     * @param string $temperatureToValidate Temperature string input to be validated.
     * @return bool Success or failure of validation (true or false).
     */
    public function validateTemperature(string $temperatureToValidate): bool
    {
        $valid = false;

        if (isset($temperatureToValidate)) {
            if ($temperatureToValidate !== '') {
                if (strlen($temperatureToValidate) <= 3) {
                    if (is_numeric($temperatureToValidate)) {
                        if (intval($temperatureToValidate) >= -30 && intval($temperatureToValidate) <= 45) {
                            $valid = true;
                        } else {
                            $this->errors['temperature'] = 'Invalid temperature value range';
                        }
                    } else {
                        $this->errors['temperature'] = 'Non-numeric temperature value';
                    }
                } else{
                    $this->errors['temperature'] = 'Invalid temperature value length';
                }
            } else {
                $this->errors['temperature'] = 'Empty temperature value';
            }
        } else {
            $this->errors['temperature'] = 'Temperature is not set';
        }

        return $valid;
    }

    /**
     * Tests to see if a keypad string input isn't empty, is exactly one character long, and is one of the valid
     * keypad input options contained in the array.
     * @param string $keypadToCheck Keypad input string to be validated.
     * @return bool Success or failure of validation (true or false).
     */
    public function validateKeypad(string $keypadToCheck): bool
    {
        $valid = false;

        if (isset($keypadToCheck)) {
            if ($keypadToCheck !== '') {
                if (strlen($keypadToCheck) == 1) {
                    if (in_array($keypadToCheck, ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '#', '*'],
                        true)) {
                        $valid = true;
                    } else {
                        $this->errors['keypad'] = 'Invalid keypad value';
                    }
                } else {
                    $this->errors['keypad'] = 'Invalid keypad value length';
                }
            } else {
                $this->errors['keypad'] = 'Empty keypad value';
            }
        } else {
            $this->errors['keypad'] = 'Keypad is not set';
        }

        return $valid;
    }

    /**
     * Tests to see if the fan string input isn't empty, and is one of the valid fan input options contained in
     * the array. Accepts uppercase, lowercase, or mixed-case inputs.
     * @param string $fanToCheck Fan input string to be validated.
     * @return bool Success or failure of validation (true or false).
     */
    public function validateFan(string $fanToCheck): bool
    {
        $valid = false;

        if (isset($fanToCheck)) {
            if ($fanToCheck !== '') {
                if (in_array($fanToCheck, ['forward', 'reverse', '1', '0', 'true', 'false'])) {
                    $valid = true;
                } else {
                    $this->errors['fan'] = 'Invalid fan value';
                }
            } else {
                $this->errors['fan'] = 'Empty fan value';
            }
        } else {
            $this->errors['fan'] = 'Fan is not set';
        }

        return $valid;
    }

    /**
     * Tests to see if the switch string input isn't empty and is one of the valid switch input options contained in
     * the array. Also tests to see if the switch number string input isn't empty and is one of the valid switch number
     * input options contained in the array. Accepts uppercase, lowercase, or mixed-case inputs.
     * @param string $switchToCheck Switch string to be validated.
     * @param string $switchNum Switch number string to be validated
     * @return bool Success or failure of validation (true or false).
     */
    public function validateSwitch(string $switchToCheck, string $switchNum): bool
    {
        $valid = false;

        if (isset($switchToCheck)) {
            if ($switchToCheck !== '') {
                if ($switchNum !== '') {
                    if(in_array(strtolower($switchNum), ['1', '2', '3', '4', 'one', 'two', 'three', 'four'])) {
                        if (in_array(strtolower($switchToCheck), ['on', 'off', '1', '0', 'true', 'false'])) {
                            $valid = true;
                        } else {
                            $this->errors[$switchNum] = 'Invalid value of switch ' . $switchNum;
                        }
                    } else {
                        $this->errors[$switchNum] = 'Invalid switch number value';
                    }
                } else {
                    $this->errors[$switchNum] = 'Empty switch number value';
                }
            } else {
                $this->errors[$switchNum] = 'Empty switch value';
            }
        } else {
            $this->errors[$switchNum] = 'Switch is not set';
        }

        return $valid;
    }
}
