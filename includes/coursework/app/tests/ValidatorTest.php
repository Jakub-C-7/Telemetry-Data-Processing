<?php

namespace Coursework;

use PHPUnit\Framework\TestCase;

/**
 * Class ValidatorTest
 *
 * Testing the validation class and the robustness of all validation methods
 *
 * @author Jakub Chamera
 *
 * @package Coursework
 */
class ValidatorTest extends TestCase
{
    /**
     * Tests temperature validation, returns true as the temperature is valid
     */
    public function testValidatorTemperatureCorrect()
    {
        $validator = new Validator();

        $input = '20';

        $this->assertTrue($validator->validateTemperature($input));
    }

    /**
     * Tests temperature validation, returns false, and a message saying that the field cannot be empty
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
     * Tests temperature validation, returns false, and a message saying that temperature is out of range
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
     * Tests temperature validation, returns false, and a message saying that temperature is out of range
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
     * Tests temperature validation, returns false, and a message saying that the temperature length is invalid
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
     * Tests temperature validation, returns true as the temperature is valid
     */
    public function testValidatorTemperatureZeroString()
    {
        $validator = new Validator();

        $input = '0';

        $this->assertTrue($validator->validateTemperature($input));
    }

    /**
     * Tests temperature validation, returns false, and a message saying that the temperature has to be numeric
     */
    public function testValidatorTemperatureInvalidInputType()
    {
        $validator = new Validator();

        $input = 'a';

        $this->assertFalse($validator->validateTemperature($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Non-numeric temperature value', $errors['temperature']);
    }

    public function testValidatorKeypadCorrect()
    {
        $validator = new Validator();

        $input = '5';

        $this->assertTrue($validator->validateKeypad($input));
    }

    public function testValidatorKeypadEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateKeypad($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty keypad value', $errors['keypad']);
    }

    public function testValidatorKeypadOutOfUpperRange()
    {
        $validator = new Validator();

        $input = '12';

        $this->assertFalse($validator->validateKeypad($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid keypad value length', $errors['keypad']);
    }

    public function testValidatorKeypadInvalidInputType()
    {
        $validator = new Validator();

        $input = '@';

        $this->assertFalse($validator->validateKeypad($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid keypad value', $errors['keypad']);
    }

    public function testValidatorKeypadZeroString()
    {
        $validator = new Validator();

        $input = '0';

        $this->assertTrue($validator->validateKeypad($input));
    }

    public function testValidatorDateTimeCorrect()
    {
        $validator = new Validator();

        $input = '20/12/2021 01:40:56';

        $this->assertTrue($validator->validateDateTime($input));
    }

    public function testValidatorDateTimeEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateDateTime($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid date', $errors['received']);
    }

    public function testValidatorDateTimeInvalidInputType()
    {
        $validator = new Validator();

        $input = 'abcdefg';

        $this->assertFalse($validator->validateDateTime($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid date', $errors['received']);
    }

    public function testValidatorDateTimeInvalidDate()
    {
        $validator = new Validator();

        $input = '40/20/1010 01:40:56';

        $this->assertFalse($validator->validateDateTime($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid date', $errors['received']);
    }

    public function testValidatorDateTimeInvalidDateFormat()
    {
        $validator = new Validator();

        $input = '4/2/2020 1:0:6';

        $this->assertFalse($validator->validateDateTime($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid date', $errors['received']);
    }

    public function testValidatorSenderPhoneNumberCorrect()
    {
        $validator = new Validator();

        $input = '441234567891';
        $role = 'sender';

        $this->assertTrue($validator->validatePhoneNumber($input, $role));
    }

    public function testValidatorReceiverPhoneNumberCorrect()
    {
        $validator = new Validator();

        $input = '441234567891';
        $role = 'receiver';

        $this->assertTrue($validator->validatePhoneNumber($input, $role));
    }

    public function testValidatorPhoneNumberEmpty()
    {
        $validator = new Validator();

        $input = '';
        $role = 'sender';

        $this->assertFalse($validator->validatePhoneNumber($input, $role));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty phone number', $errors['sender']);
    }

    public function testValidatorPhoneNumberNotBritish()
    {
        $validator = new Validator();

        $input = '221234567891';
        $role = 'sender';

        $this->assertFalse($validator->validatePhoneNumber($input, $role));

        $errors = $validator->getErrors();

        $this->assertEquals('Country code is not British', $errors['sender']);
    }

    public function testValidatorPhoneNumberInvalidInputType()
    {
        $validator = new Validator();

        $input = '44abcdefghij';
        $role = 'sender';

        $this->assertFalse($validator->validatePhoneNumber($input, $role));

        $errors = $validator->getErrors();

        $this->assertEquals('Non-numeric phone number', $errors['sender']);
    }
}