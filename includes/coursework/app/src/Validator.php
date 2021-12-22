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

        if (!empty($stringToSanitise))
        {
            $sanitisedString = filter_var($stringToSanitise, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }
        return $sanitisedString;
    }

    public function sanitiseEmail(string $emailToSanitise): string
    {
        $sanitisedEmail = false;

        if (!empty($emailToSanitise))
        {
            $sanitisedEmail = filter_var($emailToSanitise, FILTER_SANITIZE_EMAIL);
            $sanitisedEmail = filter_var($sanitisedEmail, FILTER_VALIDATE_EMAIL);
        }
        return $sanitisedEmail;
    }

    public function validateTemperature(string $temperatureToValidate): bool
    {
        $valid = false;

        if (isset($temperatureToValidate))
        {
            if (!empty($temperatureToValidate))
            {
                if (strlen($temperatureToValidate) <= 3)
                {
                    if(intval($temperatureToValidate) >= -50 && intval($temperatureToValidate) <= 150){
                        $valid = true;
                    }
                    else{
                        $this->errors['temperature'] = 'Invalid temperature range';
                    }
                }
                else{
                    $this->errors['temperature'] = 'Invalid temperature length';
                }
            }
            else
            {
                $this->errors['temperature'] = 'Empty temperature value';
            }
        }
        return $valid;
    }

    public function validateKeypad(string $keypadToCheck): bool
    {
        $valid = false;

        if (isset($keypadToCheck))
        {
            if (!empty($keypadToCheck))
            {
                if (strlen($keypadToCheck) == 1)
                {
                    if(in_array($keypadToCheck, ['1','2','3','4','5','6','7','8','9','0', '#', '*'], true)){
                        $valid = true;
                    }
                    else{
                        $this->errors['Keypad'] = 'Invalid keypad value';
                    }
                }
                else{
                    $this->errors['Keypad'] = 'Invalid keypad length';
                }
            }
            else
            {
                $this->errors['Keypad'] = 'Empty keypad value';
            }
        }
        return $valid;
    }

    public function validateFan(string $fanToCheck): bool
    {
        $valid = false;

        if (isset($fanToCheck))
        {
            if (!empty($fanToCheck))
            {
                if (strlen($fanToCheck) == 7)
                {
                    if(in_array($fanToCheck, ['forward', 'reverse', 'Forward', 'Reverse'])){
                        $valid = true;
                    }
                    else{
                        $this->errors['Fan'] == 'Invalid fan value';
                    }
                }
                else{
                    $this->errors['Fan'] = 'Invalid fan value length';
                }
            }
            else
            {
                $this->errors['Fan'] = 'Empty fan value';
            }
        }
        return $valid;
    }

    public function validateSwitch(string $switchToCheck): bool
    {
        $valid = false;

        if (isset($switchToCheck))
        {
            if (!empty($switchToCheck))
            {
                if (strlen($switchToCheck) <= 3)
                {
                    if(in_array($switchToCheck, ['on','off', 'On', 'Off'])){
                        $valid = true;
                    }
                    else{
                        $this->errors['Switch'] = 'Invalid switch value';
                    }
                }
                else{
                    $this->errors['Switch'] = 'Invalid switch length';
                }
            }
            else
            {
                $this->errors['Switch'] = 'Empty switch value';
            }
        }
        return $valid;
    }

}