<?php

/**
 * Class SoapWrapperTest tests the SoapWrapper class.
 *
 * Testing the SoapWrapper class and its success/failure scenarios when calling functions and creating connections.
 *
 * @author Jakub Chamera
 * @package Coursework
 */

namespace Coursework;

use PHPUnit\Framework\TestCase;

class SoapWrapperTest extends TestCase
{
    /**
     * @var array Valid SOAP settings to be used for testing.
     */
    static array $validSoapSettings;

    /**
     * @var array Invalid SOAP settings to be used for testing.
     */
    static array $invalidSoapSettings;

    /**
     * @var SoapWrapper Valid SOAP wrapper class to be used for testing.
     */
    static SoapWrapper $validSoapWrapper;

    /**
     * @var SoapWrapper Invalid SOAP wrapper class to be used for testing.
     */
    static SoapWrapper $invalidSoapWrapper;

    /**
     * Tests to see if a new instantiation of a SoapWrapper creates an instance of a SoapClient. Returns an instance of
     * a SoapClient.
     */
    public function testSoapCreation()
    {
        self::$validSoapSettings = [
            'connection' => [
                'wsdl' => 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl',
                'options' => [
                    'trace' => true,
                    'exceptions' => true
                ]
            ],
            'login' => [
                'username' => '21_2409490',
                'password' => 'M2mJSM2021swad!'
            ]
        ];

        self::$validSoapWrapper = new SoapWrapper(self::$validSoapSettings['connection']);

        $this->assertTrue(self::$validSoapWrapper->getClient() instanceof \SoapClient);
    }

    /**
     * Tests to see if the SoapClient can create a successful connection, returns true as the connection is made.
     */
    public function testSoapConnection()
    {
        $this->assertTrue(self::$validSoapWrapper->createSoapConnection(self::$validSoapSettings['connection']));
    }

    /**
     * Tests to see if an SoapWrapper instantiation with invalid SOAP settings won't result in the creation of a
     * SoapClient. Doesn't return an instance of a SoapClient.
     */
    public function testInvalidSoapCreation()
    {
        self::$invalidSoapSettings = [
            'connection' => [
                'wsdl' => 'invalid wsdl file',
                'options' => [
                ]
            ],
            'login' => [
                'username' => 'invalid',
                'password' => 'invalid!'
            ]
        ];

        self::$invalidSoapWrapper = new SoapWrapper(self::$invalidSoapSettings['connection']);

        $this->assertFalse(self::$invalidSoapWrapper->getClient() instanceof \SoapClient);
    }

    /**
     * Testing to see if the use of invalid SOAP settings will return false when attempting to create a new connection.
     * Returns false as an invalid connection is provided.
     */
    public function testInvalidSoapConnection()
    {
        $this->assertFalse(self::$invalidSoapWrapper->createSoapConnection(self::$invalidSoapSettings['connection']));
    }

    /**
     * Testing to see if a SOAP function call can be made. Returns the results of the successful SOAP call.
     */
    public function testSoapFunctionCall()
    {
        $parameters = [
            'username' => self::$validSoapSettings['login']['username'],
            'password' => self::$validSoapSettings['login']['password'],
            'count' => 1,
            'deviceMsisdn' => '',
            'countryCode' => '44'
        ];

        $result = self::$validSoapWrapper->performSoapFunction('', 'peekMessages', $parameters);

        $this->assertNotNull($result);
    }

    /**
     * Testing to see if a SOAP call is prevented if specified parameters are invalid. Returns null as the parameters
     * are invalid.
     */
    public function testInvalidSoapFunctionCall()
    {
        $result = self::$validSoapWrapper->performSoapFunction('', 'invalidcall', []);
        $this->assertNull($result);
    }
}

