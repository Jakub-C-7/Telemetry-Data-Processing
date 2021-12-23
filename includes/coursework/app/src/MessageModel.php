<?php
/**
 * MessageModel Class
 *
 * Methods to communicate with the M2M server and download messages using M2M functions and a SOAP client
 *
 * Author: Jakub Chamera
 * Date: 14/12/2021
 */
namespace Coursework;

class MessageModel
{
    private SoapWrapper $soapWrapper;
    private array $soapLogin;

    //The constructor creates a new instance of a Soap Wrapper and takes in the soapLogin which is set by the dependencies file
    public function __construct(SoapWrapper $soapWrapper, array $soapLogin)
    {
        $this->soapWrapper = $soapWrapper;
        $this->soapLogin = $soapLogin;
    }

    public function __destruct(){}

    //Function that downloads a set number of messages from the EE server using the 'peekMessages' function
    public function downloadMessages(string $username, int $numberOfMessages): array
    {
        $result = [];

        //Calling the soap function as per the EE guide. Getting username and password from soapLogin which comes from settings.
        $downloadedMessages = $this->soapWrapper->performSoapFunction($username, 'peekMessages', [
            'username' => $this->soapLogin['username'],
            'password' => $this->soapLogin['password'],
            'count' => $numberOfMessages,
            'deviceMsisdn' => '',
            'countryCode' => '44'
        ]);

        //handle in the event of no messages
        if ($downloadedMessages !== null) {
            $result = $downloadedMessages;
        } else {
            echo('No messages have been retrieved!');
        }

        $result = $downloadedMessages;

        return $result;
    }

}
