<?php

namespace Coursework;

use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testValidatorTemperatureCorrect()
    {
        $validator = new Validator();

        $input = '20';

        $this->assertTrue($validator->validateTemperature($input));
    }

    public function testValidatorTemperatureEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateTemperature($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty temperature value', $errors['temperature']);
    }

    public function testValidatorTemperatureOutOfUpperRange()
    {
        $validator = new Validator();

        $input = '50';

        $this->assertFalse($validator->validateTemperature($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid temperature value range', $errors['temperature']);
    }

    public function testValidatorTemperatureOutOfLowerRange()
    {
        $validator = new Validator();

        $input = '-40';

        $this->assertFalse($validator->validateTemperature($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid temperature value range', $errors['temperature']);
    }

    public function testValidatorTemperatureInvalidLength()
    {
        $validator = new Validator();

        $input = '--40';

        $this->assertFalse($validator->validateTemperature($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid temperature value length', $errors['temperature']);
    }

    public function testValidatorTemperatureZeroString()
    {
        $validator = new Validator();

        $input = '0';

        $this->assertTrue($validator->validateTemperature($input));
    }

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
}