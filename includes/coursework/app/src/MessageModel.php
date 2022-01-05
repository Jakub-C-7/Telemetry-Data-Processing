<?php

namespace Coursework;

/**
 * MessageModel Class performs SOAP functions for retrieving and sending messages.
 * 
 * The class was designed to work with and send messages to and from the EE M2M service.
 *
 * @author Jakub Chamera
 * Date: 14/12/2021
 */
class MessageModel
{
    private SoapWrapper $soapWrapper;
    private array $soapLogin;


    /**
     * Creates a new SoapWrapper and takes pre-configured soap settings.
     * @param SoapWrapper $soapWrapper An instance of the SoapWrapper being created.
     * @param array $soapLogin SOAP login details provided by the dependencies/settings files.
     */
    public function __construct(SoapWrapper $soapWrapper, array $soapLogin)
    {
        $this->soapWrapper = $soapWrapper;
        $this->soapLogin = $soapLogin;
    }

    public function __destruct(){}

    /**
     * Retrieve messages from the EE M2M service using the SOAP service.
     * @param string $username User's username used for activity logging.
     * @param int $messageNumber The max number of messages to be retrieved.
     * @return array Return an array containing all retrieved messages.
     */
    public function downloadMessages(string $username, int $messageNumber)
    {
        $result = [];
        
        $downloadedMessages = $this->soapWrapper->performSoapFunction($username, 'peekMessages', [
            'username' => $this->soapLogin['username'],
            'password' => $this->soapLogin['password'],
            'count' => $messageNumber,
            'deviceMsisdn' => '',
            'countryCode' => '44'
        ]);

        if ($downloadedMessages !== null) {
            $result = $downloadedMessages;
            //TODO Add logging here (username should ideally be added to logging)
        } else {
            $result = null;
            echo('There has been an error, no messages have been retrieved!');
            //TODO: Add logging of failure here (username should ideally be added to logging)
        }

        return $result;
    }

    /**
     * Send a message using the SOAP service.
     * @param string $username User's username used for logging.
     * @param string $destination Destination phone number of the message.
     * @param string $message Message string of the message contents being sent.
     * @return bool Return success or failure if the message has or hasn't been sent successfully.
     */
    public function sendMessage(string $username, string $destination, string $message): bool
    {
        $result = false;

        $sentMessage = $this->soapWrapper->performSoapFunction($username, 'sendMessage', [
            'username' => $this->soapLogin['username'],
            'password' => $this->soapLogin['password'],
            'deviceMSISDN' => $destination,
            'message' => $message,
            'deliveryReport' => 1,
            'mtBearer' => 'SMS'
        ]);

        if ($sentMessage !== null) {
            $result = true;
            //TODO Add logging here (username should ideally be added to logging)
        } else {
            echo('There has been an error!');
            //TODO: Add logging of failure here (username should ideally be added to logging)
        }

        return $result;
    }

}