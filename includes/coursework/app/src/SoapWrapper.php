<?php

/**
 * SoapWrapper class creates a connection to a SOAP server and calls SOAP functions.
 *
 * Functions for creating a new SOAP client, creating a new connection, and calling desired functions by name.
 *
 * @author Jakub Chamera
 * Date: 17/11/2021
 */

namespace Coursework;

use SoapClient;
use SoapFault;

class SoapWrapper
{
    /**
     * @var ?SoapClient SOAP client that can be nullified.
     */
    private ?SoapClient $client = null;

    /**
     * SoapWrapper constructor creates a new SOAP connection with the provided settings.
     * @param array $soapSettings An array containing soapSettings, provided by dependencies/settings.
     */
    public function __construct(array $soapSettings){
        $this->createSoapConnection($soapSettings);
    }

    /**
     * Gets the current instance of the SoapClient.
     * @return SoapClient|null Returns the current SoapClient or null if no connection/client exists.
     */
    public function getClient(): ?SoapClient
    {
        return $this->client;
    }

    /**
     * Sets the SoapClient to a provided SoapClient.
     * @param SoapClient|null $client Returns the SoapClient or null on failure.
     */
    public function setClient(?SoapClient $client)
    {
        $this->client = $client;
    }

    /**
     * Creates a new SOAP connection to a SOAP service.
     * @param array $soapSettings SOAP settings where the 'wsdl' and 'options' elements are used.
     * @return bool Returns the connection success or failure (true or false).
     */
    public function createSoapConnection(array $soapSettings): bool
    {
        $connection = false;

        try {
            $this->client = new SoapClient($soapSettings['wsdl'], $soapSettings['options']);
            $connection = true;
            //Todo: add logging for activity
        } catch (SoapFault $exception) {
            $message = $exception->getMessage();
            //Todo: add logging for activity + error message
        }
        return $connection;
    }

    /**
     * Performs desired SOAP function calls.
     * @param string $appUser User's username to keep track of SOAP requests for logging purposes.
     * @param string $function Name of the SOAP function to be called.
     * @param array $params Parameters required for the SOAP function call.
     * @return mixed|null Returns results of function call or null if there was an error.
     */
    public function performSoapFunction(string $appUser, string $function, array $params)
    {
        $result = null;

        //Checking if there is a SOAP connection and attempting the soap call
        if ($this->client !== null) {
            try {
                $result = $this->client->__soapCall($function, $params);
                // Todo: add logging for activity
            } catch(SoapFault $exception) {
                $errorMessage = $exception->getMessage();
               // Todo: add logging for activity + error message
            }
        }

        return $result;
    }

}
