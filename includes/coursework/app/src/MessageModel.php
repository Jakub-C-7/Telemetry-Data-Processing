<?php
/**
 * MessageModel Class
 */
namespace Coursework;

class MessageModel
{
    private SoapWrapper $soapWrapper;
    private array $soapLogin;

//    private $message_details;
//    private $sender;
//    private $receiver;
//    private $messageRef;
//    private $messageContent;
//    private $receivedDateTime;
//    private $bearer;

    //The constructor creates a new instance of a Soap Wrapper and takes in the soapLogin which is set by the dependencies file
    public function __construct(SoapWrapper $soapWrapper, array $soapLogin)
    {
        $this->soapWrapper = $soapWrapper;
        $this->soapLogin = $soapLogin;
    }

    public function __destruct(){}

    //Function that downloads messages from the EE server using the 'peekMessages' function
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

        if ($downloadedMessages !== null) {
            $result = $downloadedMessages;
        } else {
            echo('No messages have been retrieved!');
        }

        $result = $downloadedMessages;

        return $result;
    }

}
