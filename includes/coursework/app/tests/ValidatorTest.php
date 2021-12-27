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

    public function testValidatorTemperatureOutOfRangeHigh()
    {
        $validator = new Validator();

        $input = '50';

        $this->assertFalse($validator->validateTemperature($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid temperature range', $errors['temperature']);
    }

    public function testValidatorTemperatureOutOfRangeLow()
    {
        $validator = new Validator();

        $input = '-40';

        $this->assertFalse($validator->validateTemperature($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid temperature range', $errors['temperature']);
    }
}