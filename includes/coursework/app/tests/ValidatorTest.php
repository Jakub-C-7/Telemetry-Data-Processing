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

        $this->assertEquals('Empty date', $errors['received']);
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

    public function testValidatorMessageRefCorrect()
    {
        $validator = new Validator();

        $input = '0';

        $this->assertTrue($validator->validateMessageRef($input));
    }

    public function testValidatorMessageRefEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateMessageRef($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty message ref', $errors['ref']);
    }

    public function testValidatorMessageRefInvalidType()
    {
        $validator = new Validator();

        $input = 'abc';

        $this->assertFalse($validator->validateMessageRef($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Non-numeric message ref', $errors['ref']);
    }

    public function testValidatorBearerCorrectSms()
    {
        $validator = new Validator();

        $input = 'sms';

        $this->assertTrue($validator->validateBearer($input));
    }

    public function testValidatorBearerCorrectSmsUpperCase()
    {
        $validator = new Validator();

        $input = 'SMS';

        $this->assertTrue($validator->validateBearer($input));
    }

    public function testValidatorBearerCorrectSmsMixCase()
    {
        $validator = new Validator();

        $input = 'SmS';

        $this->assertTrue($validator->validateBearer($input));
    }

    public function testValidatorBearerCorrectGprs()
    {
        $validator = new Validator();

        $input = 'gprs';

        $this->assertTrue($validator->validateBearer($input));
    }

    public function testValidatorBearerCorrectGprsUpperCase()
    {
        $validator = new Validator();

        $input = 'GPRS';

        $this->assertTrue($validator->validateBearer($input));
    }

    public function testValidatorBearerCorrectGprsMixCase()
    {
        $validator = new Validator();

        $input = 'gPrS';

        $this->assertTrue($validator->validateBearer($input));
    }

    public function testValidatorBearerEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateBearer($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty bearer value', $errors['bearer']);
    }

    public function testValidatorBearerInvalidInputType()
    {
        $validator = new Validator();

        $input = '123';

        $this->assertFalse($validator->validateBearer($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid bearer', $errors['bearer']);
    }

    public function testValidatorBearerInvalidMixedInputType()
    {
        $validator = new Validator();

        $input = '1sms3';

        $this->assertFalse($validator->validateBearer($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid bearer', $errors['bearer']);
    }

    public function testValidatorFanCorrectSetTo1()
    {
        $validator = new Validator();

        $input = '1';

        $this->assertTrue($validator->validateFan($input));
    }

    public function testValidatorFanCorrectSetTo0()
    {
        $validator = new Validator();

        $input = '0';

        $this->assertTrue($validator->validateFan($input));
    }

    public function testValidatorFanCorrectSetToForward()
    {
        $validator = new Validator();

        $input = 'forward';

        $this->assertTrue($validator->validateFan($input));
    }

    public function testValidatorFanCorrectSetToReverse()
    {
        $validator = new Validator();

        $input = 'reverse';

        $this->assertTrue($validator->validateFan($input));
    }

    public function testValidatorFanCorrectSetToTrue()
    {
        $validator = new Validator();

        $input = 'true';

        $this->assertTrue($validator->validateFan($input));
    }

    public function testValidatorFanCorrectSetToFalse()
    {
        $validator = new Validator();

        $input = 'false';

        $this->assertTrue($validator->validateFan($input));
    }

    public function testValidatorFanEmpty()
    {
        $validator = new Validator();

        $input = '';

        $this->assertFalse($validator->validateFan($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty fan value', $errors['fan']);
    }

    public function testValidatorFanInvalidInputType()
    {
        $validator = new Validator();

        $input = 'fan on';

        $this->assertFalse($validator->validateFan($input));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid fan value', $errors['fan']);
    }

    public function testValidatorSwitchCorrectSetTo1()
    {
        $validator = new Validator();

        $input = '1';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    public function testValidatorSwitchCorrectSetTo0()
    {
        $validator = new Validator();

        $input = '0';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    public function testValidatorSwitchCorrectSetToOn()
    {
        $validator = new Validator();

        $input = 'on';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    public function testValidatorSwitchCorrectSetToOff()
    {
        $validator = new Validator();

        $input = 'off';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    public function testValidatorSwitchCorrectSetToTrue()
    {
        $validator = new Validator();

        $input = 'true';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    public function testValidatorSwitchCorrectSetToFalse()
    {
        $validator = new Validator();

        $input = 'false';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    public function testValidatorSwitchEmpty()
    {
        $validator = new Validator();

        $input = '';
        $switchNumber = '1';

        $this->assertFalse($validator->validateSwitch($input, $switchNumber));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty switch value', $errors[$switchNumber]);
    }

    public function testValidatorSwitchInvalidInputType()
    {
        $validator = new Validator();

        $input = 'turn on';
        $switchNumber = '1';

        $this->assertFalse($validator->validateSwitch($input, $switchNumber));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid value of switch '. $switchNumber, $errors[$switchNumber]);
    }

    public function testValidatorSwitchCorrectUpperCase()
    {
        $validator = new Validator();

        $input = 'ON';
        $switchNumber = '1';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    public function testValidatorSwitchNumberCorrectText()
    {
        $validator = new Validator();

        $input = 'on';
        $switchNumber = 'one';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    public function testValidatorSwitchNumberCorrectTextUppercase()
    {
        $validator = new Validator();

        $input = 'on';
        $switchNumber = 'ONE';

        $this->assertTrue($validator->validateSwitch($input, $switchNumber));
    }

    public function testValidatorSwitchNumberEmpty()
    {
        $validator = new Validator();

        $input = 'on';
        $switchNumber = '';

        $this->assertFalse($validator->validateSwitch($input, $switchNumber));

        $errors = $validator->getErrors();

        $this->assertEquals('Empty switch number value', $errors[$switchNumber]);
    }

    public function testValidatorSwitchNumberIncorrectText()
    {
        $validator = new Validator();

        $input = 'on';
        $switchNumber = 'five';

        $this->assertFalse($validator->validateSwitch($input, $switchNumber));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid switch number value', $errors[$switchNumber]);
    }

    public function testValidatorSwitchNumberIncorrectNumber()
    {
        $validator = new Validator();

        $input = 'on';
        $switchNumber = '5';

        $this->assertFalse($validator->validateSwitch($input, $switchNumber));

        $errors = $validator->getErrors();

        $this->assertEquals('Invalid switch number value', $errors[$switchNumber]);
    }
}