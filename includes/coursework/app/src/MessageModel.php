<?php
/**
 * MessageModel Class
 *
 * Performing SOAP functions for retrieving and sending messages to and from the EE M2M service
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

    /**
     * Retrieve messages from the EE M2M service using the SOAP service
     * @param string $username app username used for logging
     * @param int $messageNumber max number of messages to be retrieved
     * @return array return an array containing all retrieved messages
     */
    public function downloadMessages(string $username, int $messageNumber): array
    {
        $result = [];

        //Calling the soap function as per the EE guide. Getting username and password from soapLogin which comes from settings.
        $downloadedMessages = $this->soapWrapper->performSoapFunction($username, 'peekMessages', [
            'username' => $this->soapLogin['username'],
            'password' => $this->soapLogin['password'],
            'count' => $messageNumber,
            'deviceMsisdn' => '',
            'countryCode' => '44'
        ]);

        //handle in the event of no messages
        if ($downloadedMessages !== null) {
            $result = $downloadedMessages;
        } else {
            echo('There has been an error, no messages have been retrieved!');
        }

        return $result;
    }

    /**
     * Send a message to using the SOAP service
     * @param string $username used to initiate method, for reference, and logging of the application
     * @param string $destination the destination phone number
     * @param string $message the desired message being sent
     * @return bool used to return if the message was sent successfuly or not
     *
     */
    public function sendMessage(string $username, string $destination, string $message): bool
    {
        $result = false;

        //Calling the soap function as per the EE guide. Getting username and password from soapLogin which comes from settings.
        $sentMessage = $this->soapWrapper->performSoapFunction($username, 'sendMessage', [
            'username' => $this->soapLogin['username'],
            'password' => $this->soapLogin['password'],
            'deviceMSISDN' => $destination,
            'message' => $message,
            'deliveryReport' => 1,
            'mtBearer' => 'SMS'
        ]);

        //handle in the event of no messages
        if ($sentMessage !== null) {
            $result = true;
        } else {
            echo('There has been an error!');
        }

        return $result;
    }

}
