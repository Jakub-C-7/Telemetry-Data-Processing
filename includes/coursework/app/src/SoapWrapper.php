<?php
/**
 * SoapWrapper Class
 * Connects to the Soap server and enables Soap function calls
 *
 * Author: Jakub Chamera
 * Date: 17/11/2021
 */

namespace Coursework;

use SoapClient;
use SoapFault;

class SoapWrapper
{
    /**
     * The SOAP client that can be nullified.
     * @var ?SoapClient
     */
    private ?SoapClient $client = null;

    public function __construct(array $soapSettings){
//      $this->client = new SoapClient(WSDL);
        $this->createSoapConnection($soapSettings);
//        var_dump($soapSettings);
    }

//    public function __construct(){}
    public function __destruct(){}

    public function getClient(): ?SoapClient
    {
        return $this->client;
    }

    public function setClient(?SoapClient $client)
    {
        $this->client = $client;
    }

    public function createSoapConnection(array $soapSettings): bool
    {
        $connection = false;

        try {
            $this->client = new SoapClient($soapSettings['wsdl'], $soapSettings['options']);
            $connection = true;
        } catch (SoapFault $exception) {
            $message = $exception->getMessage();
            echo('SOAP Connection Error' . $exception);
        }
        return $connection;
    }

    public function performSoapFunction(string $appUser, string $function, array $params)
    {
        $result = null;

        //Checking if there is a SOAP connection
        if ($this->client !== null) {

            $result = $this->client->__soapCall($function, $params);
        }

        return $result;
    }

}