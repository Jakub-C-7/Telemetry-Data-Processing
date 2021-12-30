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

    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function sanitiseString(string $stringToSanitise): string
    {
        $sanitisedString = false;

        if (!empty($stringToSanitise)) {
            $sanitisedString = filter_var($stringToSanitise, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        return $sanitisedString;
    }

    public function sanitiseEmail(string $emailToSanitise): string
    {
        $sanitisedEmail = false;

        if (!empty($emailToSanitise)) {
            $sanitisedEmail = filter_var($emailToSanitise, FILTER_SANITIZE_EMAIL);
            $sanitisedEmail = filter_var($sanitisedEmail, FILTER_VALIDATE_EMAIL);
        }

        return $sanitisedEmail;
    }

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

    public function validateKeypad(string $keypadToCheck): bool
    {
        $valid = false;

        if (isset($keypadToCheck)) {
            if ($keypadToCheck !== '') {
                if (strlen($keypadToCheck) == 1) {
                    if (in_array($keypadToCheck, ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '#', '*'], true)) {
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
