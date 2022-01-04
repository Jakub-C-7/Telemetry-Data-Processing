<?php

namespace Coursework;

use PHPUnit\Framework\TestCase;

/**
 * Class ValidatorTest tests the Validator class.
 *
 * Testing the validation class and the robustness of validation methods.
 *
 * @author Jakub Chamera
 * @package Coursework
 */
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
}