<?php

/**
 * Class ValidatorTest tests the Validator class.
 *
 * Testing the validation class and the robustness of validation methods. Validation tests include the validation of
 * message content, message metadata, and user details.
 *
 * @author Jakub Chamera
 * @package Coursework
 */

namespace Coursework;

use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * Tests temperature validation, returns true as the temperature is valid.
     * @return void
     */
    public function testValidatorTemperatureCorrect()
    {
        $validator = new Validator();

        $input = '20';

        $this->assertTrue($validator->validateTemperature($input));
    }

    /**
     * Tests temperature validation when empty, returns false, and a message saying that temperature is empty.
     * @return void
     */
    public function testValidatorTemperatureEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateTemperature($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty temperature value', $errors['temperature']);
    }

    /**
     * Tests temperature validation, returns false, and a message saying that temperature is out of range.
     * @return void
     */
    public function testValidatorTemperatureOutOfUpperRange()
    {
        $validator = new Validator();

        $input = '50';

        $this->assertFalse($validator->validateTemperature($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid temperature value range', $errors['temperature']);
    }

    /**
     * Tests temperature validation, returns false, and a message saying that temperature is out of range.
     * @return void
     */
    public function testValidatorTemperatureOutOfLowerRange()
    {
        $validator = new Validator();

        $input = '-40';

        $this->assertFalse($validator->validateTemperature($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid temperature value range', $errors['temperature']);
    }

    /**
     * Tests temperature validation, returns false, and a message saying that the temperature length is invalid.
     * @return void
     */
    public function testValidatorTemperatureInvalidLength()
    {
        $validator = new Validator();

        $input = '--40';

        $this->assertFalse($validator->validateTemperature($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid temperature value length', $errors['temperature']);
    }

    /**
     * Tests temperature validation, returns true as the temperature is valid.
     * @return void
     */
    public function testValidatorTemperatureZeroString()
    {
        $validator = new Validator();

        $input = '0';

        $this->assertTrue($validator->validateTemperature($input));
    }

    /**
     * Tests temperature validation, returns false, and a message saying that the temperature has to be numeric.
     * @return void
     */
    public function testValidatorTemperatureInvalidInputType()
    {
        $validator = new Validator();

        $input = 'a';

        $this->assertFalse($validator->validateTemperature($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Non-numeric temperature value', $errors['temperature']);
    }

    /**
     * Tests keypad validation, returns true as the keypad is valid.
     * @return void
     */
    public function testValidatorKeypadCorrect()
    {
        $validator = new Validator();

        $input = '5';

        $this->assertTrue($validator->validateKeypad($input));
    }

    /**
     * Tests keypad validation, returns false, and a message saying that the keypad is empty.
     * @return void
     */
    public function testValidatorKeypadEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateKeypad($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty keypad value', $errors['keypad']);
    }

    /**
     * Tests keypad validation, returns false, and a message saying that keypad is out of range.
     * @return void
     */
    public function testValidatorKeypadOutOfUpperRange()
    {
        $validator = new Validator();

        $input = '12';

        $this->assertFalse($validator->validateKeypad($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid keypad value length', $errors['keypad']);
    }

    /**
     * Tests keypad validation, returns false, and a message saying that keypad value is invalid.
     * @return void
     */
    public function testValidatorKeypadInvalidInputType()
    {
        $validator = new Validator();

        $input = '@';

        $this->assertFalse($validator->validateKeypad($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid keypad value', $errors['keypad']);
    }

    /**
     * Tests keypad validation, returns true as the keypad is valid.
     * @return void
     */
    public function testValidatorKeypadZeroString()
    {
        $validator = new Validator();

        $input = '0';

        $this->assertTrue($validator->validateKeypad($input));
    }

    /**
     * Tests datetime validation, returns true as the datetime is valid.
     * @return void
     */
    public function testValidatorDateTimeCorrect()
    {
        $validator = new Validator();

        $input = '20/12/2021 01:40:56';

        $this->assertTrue($validator->validateDateTime($input));
    }

    /**
     * Tests datetime validation, returns false, and a message saying that datetime value is empty.
     * @return void
     */
    public function testValidatorDateTimeEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateDateTime($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty date', $errors['received']);
    }

    /**
     * Tests datetime validation, returns false, and a message saying that datetime is invalid.
     * @return void
     */
    public function testValidatorDateTimeInvalidInputType()
    {
        $validator = new Validator();

        $input = 'abcdefg';

        $this->assertFalse($validator->validateDateTime($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid date', $errors['received']);
    }

    /**
     * Tests datetime validation, returns false, and a message saying that datetime is invalid.
     * @return void
     */
    public function testValidatorDateTimeInvalidDate()
    {
        $validator = new Validator();

        $input = '40/20/1010 01:40:56';

        $this->assertFalse($validator->validateDateTime($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid date', $errors['received']);
    }

    /**
     * Tests datetime validation, returns false, and a message saying that datetime is invalid.
     * @return void
     */
    public function testValidatorDateTimeInvalidDateFormat()
    {
        $validator = new Validator();

        $input = '4/2/2020 1:0:6';

        $this->assertFalse($validator->validateDateTime($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid date', $errors['received']);
    }

    /**
     * Tests sender phone number validation, returns true as the number is valid.
     * @return void
     */
    public function testValidatorSenderPhoneNumberCorrect()
    {
        $validator = new Validator();

        $input = '441234567891';
        $role = 'sender';

        $this->assertTrue($validator->validatePhoneNumber($input, $role));
    }

    /**
     * Tests receiver phone number validation, returns true as the number is valid.
     * @return void
     */
    public function testValidatorReceiverPhoneNumberCorrect()
    {
        $validator = new Validator();

        $input = '441234567891';
        $role = 'receiver';

        $this->assertTrue($validator->validatePhoneNumber($input, $role));
    }

    /**
     * Tests phone number validation, returns false, and a message saying that the phone number is empty.
     * @return void
     */
    public function testValidatorPhoneNumberEmpty()
    {
        $validator = new Validator();

        $input = '';
        $role = 'sender';

        $this->assertFalse($validator->validatePhoneNumber($input, $role));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty phone number', $errors['sender']);
    }

    /**
     * Tests phone number validation, returns false, and a message saying that the phone number is not british.
     * @return void
     */
    public function testValidatorPhoneNumberNotBritish()
    {
        $validator = new Validator();

        $input = '221234567891';
        $role = 'sender';

        $this->assertFalse($validator->validatePhoneNumber($input, $role));

        $errors = $validator->getErrors();

        $this->assertEquals('Country code is not British', $errors['sender']);
    }

    /**
     * Tests phone number validation, returns false, and a message saying that the phone number is invalid.
     * @return void
     */
    public function testValidatorPhoneNumberInvalidInputType()
    {
        $validator = new Validator();

        $input = '44abcdefghij';
        $role = 'sender';

        $this->assertFalse($validator->validatePhoneNumber($input, $role));

        $errors = $validator->getErrors();

        $this->assertEquals('Non-numeric phone number', $errors['sender']);
    }

    /**
     * Tests message ref validation, returns true as the message ref is valid.
     * @return void
     */
    public function testValidatorMessageRefCorrect()
    {
        $validator = new Validator();

        $input = '0';

        $this->assertTrue($validator->validateMessageRef($input));
    }

    /**
     * Tests message ref validation, returns false, and a message saying that the message ref is empty.
     * @return void
     */
    public function testValidatorMessageRefEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateMessageRef($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty message ref', $errors['ref']);
    }

    /**
     * Tests message ref validation, returns false, and a message saying that the message ref is not numeric.
     * @return void
     */
    public function testValidatorMessageRefInvalidType()
    {
        $validator = new Validator();

        $input = 'abc';

        $this->assertFalse($validator->validateMessageRef($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Non-numeric message ref', $errors['ref']);
    }

    /**
     * Tests bearer validation, returns true as the bearer is valid.
     * @return void
     */
    public function testValidatorBearerCorrectSms()
    {
        $validator = new Validator();

        $input = 'sms';

        $this->assertTrue($validator->validateBearer($input));
    }

    /**
     * Tests bearer validation, returns true as the bearer is valid.
     * @return void
     */
    public function testValidatorBearerCorrectSmsUpperCase()
    {
        $validator = new Validator();

        $input = 'SMS';

        $this->assertTrue($validator->validateBearer($input));
    }

    /**
     * Tests bearer validation, returns true as the bearer is valid.
     * @return void
     */
    public function testValidatorBearerCorrectSmsMixCase()
    {
        $validator = new Validator();

        $input = 'SmS';

        $this->assertTrue($validator->validateBearer($input));
    }

    /**
     * Tests bearer validation, returns true as the bearer is valid.
     * @return void
     */
    public function testValidatorBearerCorrectGprs()
    {
        $validator = new Validator();

        $input = 'gprs';

        $this->assertTrue($validator->validateBearer($input));
    }

    /**
     * Tests bearer validation, returns true as the bearer is valid.
     * @return void
     */
    public function testValidatorBearerCorrectGprsUpperCase()
    {
        $validator = new Validator();

        $input = 'GPRS';

        $this->assertTrue($validator->validateBearer($input));
    }

    /**
     * Tests bearer validation, returns true as the bearer is valid.
     * @return void
     */
    public function testValidatorBearerCorrectGprsMixCase()
    {
        $validator = new Validator();

        $input = 'gPrS';

        $this->assertTrue($validator->validateBearer($input));
    }

    /**
     * Tests bearer validation, returns false, and a message saying that bearer is empty.
     * @return void
     */
    public function testValidatorBearerEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateBearer($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty bearer value', $errors['bearer']);
    }

    /**
     * Tests bearer validation, returns false, and a message saying that bearer is invalid.
     * @return void
     */
    public function testValidatorBearerInvalidInputType()
    {
        $validator = new Validator();

        $input = '123';

        $this->assertFalse($validator->validateBearer($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid bearer', $errors['bearer']);
    }

    /**
     * Tests bearer validation, returns false, and a message saying that bearer is invalid.
     * @return void
     */
    public function testValidatorBearerInvalidMixedInputType()
    {
        $validator = new Validator();

        $input = '1sms3';

        $this->assertFalse($validator->validateBearer($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid bearer', $errors['bearer']);
    }

    /**
     * Tests fan validation, returns true as the fan is valid.
     * @return void
     */
    public function testValidatorFanCorrectSetTo1()
    {
        $validator = new Validator();

        $input = '1';

        $this->assertTrue($validator->validateFan($input));
    }

    /**
     * Tests fan validation, returns true as the fan is valid.
     * @return void
     */
    public function testValidatorFanCorrectSetTo0()
    {
        $validator = new Validator();

        $input = '0';

        $this->assertTrue($validator->validateFan($input));
    }

    /**
     * Tests fan validation, returns true as the fan is valid.
     * @return void
     */
    public function testValidatorFanCorrectSetToForward()
    {
        $validator = new Validator();

        $input = 'forward';

        $this->assertTrue($validator->validateFan($input));
    }

    /**
     * Tests fan validation, returns true as the fan is valid.
     * @return void
     */
    public function testValidatorFanCorrectSetToReverse()
    {
        $validator = new Validator();

        $input = 'reverse';

        $this->assertTrue($validator->validateFan($input));
    }

    /**
     * Tests fan validation, returns true as the fan is valid.
     * @return void
     */
    public function testValidatorFanCorrectSetToTrue()
    {
        $validator = new Validator();

        $input = 'true';

        $this->assertTrue($validator->validateFan($input));
    }

    /**
     * Tests fan validation, returns true as the fan is valid.
     * @return void
     */
    public function testValidatorFanCorrectSetToFalse()
    {
        $validator = new Validator();

        $input = 'false';

        $this->assertTrue($validator->validateFan($input));
    }

    /**
     * Tests fan validation, returns false, and a message saying that the fan is empty.
     * @return void
     */
    public function testValidatorFanEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateFan($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty fan value', $errors['fan']);
    }

    /**
     * Tests fan validation, returns false, and a message saying that the fan is invalid.
     * @return void
     */
    public function testValidatorFanInvalidInputType()
    {
        $validator = new Validator();

        $input = 'fan on';

        $this->assertFalse($validator->validateFan($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid fan value', $errors['fan']);
    }

    /**
     * Tests switch validation, returns true as the switch is valid.
     * @return void
     */
    public function testValidatorSwitchCorrectSetTo1()
    {
        $validator = new Validator();

        $input = '1';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    /**
     * Tests switch validation, returns true as the switch is valid.
     * @return void
     */
    public function testValidatorSwitchCorrectSetTo0()
    {
        $validator = new Validator();

        $input = '0';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    /**
     * Tests switch validation, returns true as the switch is valid.
     * @return void
     */
    public function testValidatorSwitchCorrectSetToOn()
    {
        $validator = new Validator();

        $input = 'on';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    /**
     * Tests switch validation, returns true as the switch is valid.
     * @return void
     */
    public function testValidatorSwitchCorrectSetToOff()
    {
        $validator = new Validator();

        $input = 'off';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    /**
     * Tests switch validation, returns true as the switch is valid.
     * @return void
     */
    public function testValidatorSwitchCorrectSetToTrue()
    {
        $validator = new Validator();

        $input = 'true';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    /**
     * Tests switch validation, returns true as the switch is valid.
     * @return void
     */
    public function testValidatorSwitchCorrectSetToFalse()
    {
        $validator = new Validator();

        $input = 'false';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    /**
     * Tests switch validation, returns false, and a message saying that the switch is empty.
     * @return void
     */
    public function testValidatorSwitchEmpty()
    {
        $validator = new Validator();

        $input = '';
        $switchNumber = '1';

        $this->assertFalse($validator->validateSwitch($input, $switchNumber));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty switch value', $errors[$switchNumber]);
    }

    /**
     * Tests switch validation, returns false, and a message saying that the switch is invalid.
     * @return void
     */
    public function testValidatorSwitchInvalidInputType()
    {
        $validator = new Validator();

        $input = 'turn on';
        $switchNumber = '1';

        $this->assertFalse($validator->validateSwitch($input, $switchNumber));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid value of switch '. $switchNumber, $errors[$switchNumber]);
    }

    /**
     * Tests switch validation, returns true as the switch is valid.
     * @return void
     */
    public function testValidatorSwitchCorrectUpperCase()
    {
        $validator = new Validator();

        $input = 'ON';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    /**
     * Tests switch validation, returns true as the switch is valid.
     * @return void
     */
    public function testValidatorSwitchNumberCorrectText()
    {
        $validator = new Validator();

        $input = 'on';
        $switchNumber = 'one';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    /**
     * Tests switch validation, returns true as the switch is valid.
     * @return void
     */
    public function testValidatorSwitchNumberCorrectTextUppercase()
    {
        $validator = new Validator();

        $input = 'on';
        $switchNumber = 'ONE';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    /**
     * Tests switch validation, returns false, and a message saying that the switch number is empty.
     * @return void
     */
    public function testValidatorSwitchNumberEmpty()
    {
        $validator = new Validator();

        $input = 'on';
        $switchNumber = '';

        $this->assertFalse($validator->validateSwitch($input, $switchNumber));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty switch number value', $errors[$switchNumber]);
    }

    /**
     * Tests switch validation, returns false, and a message saying that the switch number is invalid.
     * @return void
     */
    public function testValidatorSwitchNumberIncorrectText()
    {
        $validator = new Validator();

        $input = 'on';
        $switchNumber = 'five';

        $this->assertFalse($validator->validateSwitch($input, $switchNumber));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid switch number value', $errors[$switchNumber]);
    }

    /**
     * Tests switch validation, returns false, and a message saying that the switch number is invalid.
     * @return void
     */
    public function testValidatorSwitchNumberIncorrectNumber()
    {
        $validator = new Validator();

        $input = 'on';
        $switchNumber = '5';

        $this->assertFalse($validator->validateSwitch($input, $switchNumber));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid switch number value', $errors[$switchNumber]);
    }

    /**
     * Tests user email validation, returns true as the supplied email is valid.
     * @return void
     */
    public function testUserEmailCorrect()
    {
        $validator = new Validator();

        $input = 'test@test.com';

        $this->assertTrue($validator->validateEmail($input));
    }

    /**
     * Tests user email validation, returns false and a message saying that the email cannot be blank.
     * @return void
     */
    public function testUserEmailEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateEmail($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Email cannot be blank', $errors['email']);
    }

    /**
     * Tests user email validation, returns false and a message saying that the email cannot be smaller than 3
     * characters.
     * @return void
     */
    public function testUserEmailTooShort()
    {
        $validator = new Validator();

        $input = 'a';

        $this->assertFalse($validator->validateEmail($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Email cannot be smaller that 3 characters or longer than 255 characters', $errors['email']);
    }

    /**
     * Tests user email validation, returns false and a message saying that the email must be a valid email address.
     * @return void
     */
    public function testUserEmailInvalidFormat1()
    {
        $validator = new Validator();

        $input = 'abcdefghijk';

        $this->assertFalse($validator->validateEmail($input));

        $errors = $validator->getErrors();

        $this->assertEquals('The email must be a valid email address', $errors['email']);
    }

    /**
     * Tests user email validation, returns false and a message saying that the email must be a valid email address.
     * @return void
     */
    public function testUserEmailInvalidFormat2()
    {
        $validator = new Validator();

        $input = 'abcdefghijk@';

        $this->assertFalse($validator->validateEmail($input));

        $errors = $validator->getErrors();

        $this->assertEquals('The email must be a valid email address', $errors['email']);
    }

    /**
     * Tests user email validation, returns false and a message saying that the email must be a valid email address.
     * @return void
     */
    public function testUserEmailInvalidFormat3()
    {
        $validator = new Validator();

        $input = 'abc@.com';

        $this->assertFalse($validator->validateEmail($input));

        $errors = $validator->getErrors();

        $this->assertEquals('The email must be a valid email address', $errors['email']);
    }

    /**
     * Tests user email validation, returns false and a message saying that the email must be a valid email address.
     * @return void
     */
    public function testUserEmailInvalidFormat4()
    {
        $validator = new Validator();

        $input = 'abc@g.';

        $this->assertFalse($validator->validateEmail($input));

        $errors = $validator->getErrors();

        $this->assertEquals('The email must be a valid email address', $errors['email']);
    }

    /**
     * Tests user email validation, returns false and a message saying that the email must be a valid email address.
     * @return void
     */
    public function testUserEmailInvalidFormat5()
    {
        $validator = new Validator();

        $input = 'abc@g';

        $this->assertFalse($validator->validateEmail($input));

        $errors = $validator->getErrors();

        $this->assertEquals('The email must be a valid email address', $errors['email']);
    }

    /**
     * Tests user email validation, returns false and a message saying that the email must be a valid email address.
     * @return void
     */
    public function testUserEmailInvalidFormat6()
    {
        $validator = new Validator();

        $input = '@gmail.com';

        $this->assertFalse($validator->validateEmail($input));

        $errors = $validator->getErrors();

        $this->assertEquals('The email must be a valid email address', $errors['email']);
    }

    /**
     * Tests user password validation, returns true as the password is valid.
     * @return void
     */
    public function testUserPasswordCorrect()
    {
        $validator = new Validator();

        $input = 'Password1925';

        $this->assertTrue($validator->validatePassword($input));
    }

    /**
     * Tests user password validation, returns false and a message saying that the password cannot be blank.
     * @return void
     */
    public function testUserPasswordEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validatePassword($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Password cannot be blank', $errors['password']);
    }

    /**
     * Tests user password validation, returns false and a message saying that the password cannot be smaller than 8
     * characters.
     * @return void
     */
    public function testUserPasswordTooShort()
    {
        $validator = new Validator();

        $input = 'Pass';

        $this->assertFalse($validator->validatePassword($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Password cannot be smaller that 8 characters or longer than 255 
                characters', $errors['password']);
    }

    /**
     * Tests user password validation, returns false and a message saying that the password must contain a mixture of
     * uppercase and lowercase letters.
     * @return void
     */
    public function testUserPasswordAllLowercase()
    {
        $validator = new Validator();

        $input = 'password123';

        $this->assertFalse($validator->validatePassword($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Password needs to contain a mixture of uppercase and lowercase 
                    letters', $errors['password']);
    }

    /**
     * Tests user password validation, returns false and a message saying that the password must contain a mixture of
     * uppercase and lowercase letters.
     * @return void
     */
    public function testUserPasswordAllUppercase()
    {
        $validator = new Validator();

        $input = 'PASSWORD123';

        $this->assertFalse($validator->validatePassword($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Password needs to contain a mixture of uppercase and lowercase 
                    letters', $errors['password']);
    }

    /**
     * Tests user password validation, returns false and a message saying that the password must contain at least one
     * number.
     * @return void
     */
    public function testUserPasswordAllLetters()
    {
        $validator = new Validator();

        $input = 'PasswordPassword';

        $this->assertFalse($validator->validatePassword($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Password needs to contain at least one number', $errors['password']);
    }

    /**
     * Tests user password validation, returns false and a message saying that the password must contain a mixture of
     * uppercase and lowercase letters.
     * @return void
     */
    public function testUserPasswordAllNumbers()
    {
        $validator = new Validator();

        $input = '12345678910';

        $this->assertFalse($validator->validatePassword($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Password needs to contain a mixture of uppercase and lowercase 
                    letters', $errors['password']);
    }

    /**
     * Tests user confirm password validation, returns true as the confirm password matches the original password.
     * @return void
     */
    public function testUserConfirmPasswordCorrect()
    {
        $validator = new Validator();

        $password = 'Password1925';
        $confirmPassword = 'Password1925';

        $this->assertTrue($validator->validateConfirmPassword($confirmPassword, $password));
    }

    /**
     * Tests user confirm password validation, returns false and a message saying that the confirm password cannot be
     * empty.
     * @return void
     */
    public function testUserConfirmPasswordEmpty()
    {
        $validator = new Validator();

        $password = 'Password1925';
        $confirmPassword = '';

        $this->assertFalse($validator->validateConfirmPassword($confirmPassword, $password));

        $errors = $validator->getErrors();

        $this->assertEquals('Confirm password cannot be blank', $errors['confirmPassword']);
    }

    /**
     * Tests user confirm password validation, returns false and a message saying that the confirm password does not
     * match with original password.
     * @return void
     */
    public function testUserConfirmPasswordDoesNotMatch()
    {
        $validator = new Validator();

        $password = 'Password1925';
        $confirmPassword = 'Password123';

        $this->assertFalse($validator->validateConfirmPassword($confirmPassword, $password));

        $errors = $validator->getErrors();

        $this->assertEquals('Confirm password does not match with original password', $errors['confirmPassword']);
    }

    /**
     * Tests user confirm password validation, returns false and a message saying that the confirm password does not
     * match with original password.
     * @return void
     */
    public function testUserConfirmPasswordUppercaseDoesNotMatch()
    {
        $validator = new Validator();

        $password = 'Password1925';
        $confirmPassword = 'PASSWORD1925';

        $this->assertFalse($validator->validateConfirmPassword($confirmPassword, $password));

        $errors = $validator->getErrors();

        $this->assertEquals('Confirm password does not match with original password', $errors['confirmPassword']);
    }

    /**
     * Tests user confirm password validation, returns false and a message saying that the confirm password does not
     * match with original password.
     * @return void
     */
    public function testUserConfirmPasswordLowercaseDoesNotMatch()
    {
        $validator = new Validator();

        $password = 'Password1925';
        $confirmPassword = 'password1925';

        $this->assertFalse($validator->validateConfirmPassword($confirmPassword, $password));

        $errors = $validator->getErrors();

        $this->assertEquals('Confirm password does not match with original password', $errors['confirmPassword']);
    }
}

