<?php

namespace Coursework;

use DateTime;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Ramsey\Uuid\Uuid;

/**
 * Class DoctrineSqlQueries contains all database access using Doctrine's QueryBuilder.
 *
 * A QueryBuilder provides an API that is designed for conditionally constructing a DQL query in several steps.
 *
 * It provides a set of classes and methods that is able to programmatically build queries, and also provides
 * a fluent API.
 * This means that you can change between one methodology to the other as you want, or just pick a preferred one.
 *
 * From https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/query-builder.html
 *
 * @package Coursework
 */
class DoctrineSqlQueries
{
    public function __construct(){}

    public function __destruct(){}

    /**
     * A function to retrieve the latest message from the messages table.
     * @param QueryBuilder $queryBuilder builds the command to retrieve the data.
     * @return array The array of messages that will only contain one message at index 0.
     * @return false If an exception is caught.
     */
    public static function retrieveLatestMessage(QueryBuilder $queryBuilder)
    {
        $store_result = [];

        $queryBuilder = $queryBuilder->select(
            'source',
            'destination',
            'message_received_time',
            'switch1',
            'switch2',
            'switch3',
            'switch4',
            'fan',
            'temperature',
            'keypad'
        )
            ->from('messages', 'm')->orderBy('message_received_time', 'DESC')
            ->setMaxResults(1);

        try {
            $store_result['outcome'] = $queryBuilder->executeQuery();
            $store_result['result'] = $store_result['outcome']->fetchAllAssociative();
            $store_result['sql_query'] = $queryBuilder->getSQL();
        } catch (Exception $ex ) {
            $store_result['outcome'] = false;
            $store_result['sql_query'] = $queryBuilder->getSQL();

            return $store_result;
        }

        return $store_result;
    }

    /**
     * A function to retrieve every message from the database.
     * @param $queryBuilder QueryBuilder buids the command to retrieve the data.
     * @return mixed The array of messages.
     */
    public static function retrieveAllMessages(QueryBuilder $queryBuilder) {
        $store_result = [];

        $queryBuilder = $queryBuilder->select(
            'source',
            'destination',
            'message_received_time',
            'switch1',
            'switch2',
            'switch3',
            'switch4',
            'fan',
            'temperature',
            'keypad'
        )->from('messages');

        try  {
            $store_result['outcome'] = $queryBuilder->executeQuery();
            $store_result['result'] = $store_result['outcome']->fetchAllAssociative();
            $store_result['sql_query'] = $queryBuilder->getSQL();

            return $store_result;
        } catch (Exception $ex ) {
            $store_result['outcome'] = false;
            $store_result['sql_query'] = $queryBuilder->getSQL();

            return $store_result;
        }
    }

    /**
     * A function to insert mobile numbers into the mobile_numbers table.
     * @param $queryBuilder QueryBuilder builds the command to insert data.
     * @param $mobile_number string The mobile number to insert.
     * @return array Returns information about how the transaction went.
     */
    public static function insertMobileNumber(QueryBuilder $queryBuilder, string $mobile_number): array
    {
        $store_result = [];

        $queryBuilder = $queryBuilder->insert('mobile_numbers')
            ->values([
                'mobile_number' => ':mobile_number'
            ])
            ->setParameters([
                'mobile_number' => $mobile_number
            ]);

        $store_result['outcome'] = $queryBuilder->executeStatement();
        $store_result['sql_query'] = $queryBuilder->getSQL();

        return $store_result;
    }

    /**
     * A function to query the database to check if a mobile number exists.
     * @param $queryBuilder QueryBuilder builds the query to retrieve data
     * @param $mobile_number string The mobile number to check if it is on the database
     * @return bool True if the mobile number exists, false if it does not exist
     */
    public static function checkMobileNumberExists(QueryBuilder $queryBuilder, string $mobile_number): bool
    {
        $exists = true;
        $queryBuilder = $queryBuilder->select('mobile_number')
            ->from('mobile_numbers', 'm')
            ->where('m.mobile_number = '.$queryBuilder->createNamedParameter($mobile_number));

        $query = $queryBuilder->executeQuery();
        $result = $query->fetchOne();

        if ($result == false) {
            $exists = false;
        }

        return $exists;
    }

    /**
     * A function to insert message data into the messages table.
     * @param $queryBuilder QueryBuilder builds the query to retrieve data
     * @param $cleaned_parameters array The message data to be inserted.
     * @return array The information about how the transaction went.
     */
    public static function insertMessageData(QueryBuilder $queryBuilder, array $cleaned_parameters): array
    {
        $store_result = [];

        $message_id = Uuid::uuid4()->toString();
        $source = $cleaned_parameters['source'];
        $destination = $cleaned_parameters['destination'];
        $message_received_time = $cleaned_parameters['received'];
        $bearer = $cleaned_parameters['bearer'];
        $message_ref = $cleaned_parameters['ref'];
        $switch1 = $cleaned_parameters['switchOne'];
        $switch2 = $cleaned_parameters['switchTwo'];
        $switch3 = $cleaned_parameters['switchThree'];
        $switch4 = $cleaned_parameters['switchFour'];
        $fan = $cleaned_parameters['fan'];
        $temperature = $cleaned_parameters['temperature'];
        $keypad = $cleaned_parameters['keypad'];

        $queryBuilder = $queryBuilder->insert('messages')
            ->values([
                'message_id' => ':message_id',
                'source' => ':source',
                'destination' => ':destination',
                'message_received_time' => ':message_received_time',
                'bearer' => ':bearer',
                'message_ref' => ':message_ref',
                'switch1' => ':switch1',
                'switch2' => ':switch2',
                'switch3' => ':switch3',
                'switch4' => ':switch4',
                'fan' => ':fan',
                'temperature' => ':temperature',
                'keypad' => ':keypad'
            ])
            ->setParameters([
                'message_id' => $message_id,
                'source' => $source,
                'destination' => $destination,
                'message_received_time' => $message_received_time,
                'bearer' => $bearer,
                'message_ref' => $message_ref,
                'switch1' => $switch1,
                'switch2' => $switch2,
                'switch3' => $switch3,
                'switch4' => $switch4,
                'fan' => $fan,
                'temperature' => $temperature,
                'keypad' => $keypad
            ]);

        $store_result['outcome'] = $queryBuilder->executeStatement();
        $store_result['sql_query'] = $queryBuilder->getSQL();

        return $store_result;
    }

    /**
     * A function to query the database to check if a message exists.
     * @param $queryBuilder QueryBuilder builds the query to retrieve data
     * @param $sender string The mobile number used for the sender
     * @param $recipient string The mobile number used for the recipient
     * @param $dateTimeReceived string The date time stamp of the message when received
     * @return bool True if the message exists in the database, false if it does not exist
     */
    public static function checkMessageExists(QueryBuilder $queryBuilder,
                                              string $sender,
                                              string $recipient,
                                              string $dateTimeReceived): bool
    {
        $exists = true;

        $queryBuilder = $queryBuilder->select('message_id')
            ->from('messages', 'm')
            ->where('source = '.$queryBuilder->createNamedParameter($sender))
            ->andWhere('destination = '.$queryBuilder->createNamedParameter($recipient))
            ->andWhere('message_received_time = '.$queryBuilder->createNamedParameter($dateTimeReceived));

        $query = $queryBuilder->executeQuery();
        $result = $query->fetchOne();

        if ($result == false) {
            $exists = false;
        }

        return $exists;
    }

    /**
     * A function to insert users into the database users table.
     * @param QueryBuilder $queryBuilder Builds the query for storing the data.
     * @param array $cleaned_parameters The user data to be inserted.
     * @return array Array containing function outcome information.
     */
    public static function insertUser(QueryBuilder $queryBuilder, array $cleaned_parameters): array
    {
        $store_result = [];

        $user_id = Uuid::uuid4()->toString();
        $email = $cleaned_parameters['email'];
        $password = $cleaned_parameters['password'];
        $phoneNumber = $cleaned_parameters['phoneNumber'];

        $queryBuilder = $queryBuilder->insert('users')
            ->values([
                'user_id' => ':user_id',
                'email' => ':email',
                'password' => ':password',
                'phoneNumber' => ':phoneNumber',
            ])
            ->setParameters([
                'user_id' => $user_id,
                'email' => $email,
                'password' => $password,
                'phoneNumber' => $phoneNumber,
            ]);

        $store_result['outcome'] = $queryBuilder->executeStatement();
        $store_result['sql_query'] = $queryBuilder->getSQL();

        return $store_result;
    }

    /**
     * A function to query the database to check if a user already exists via email.
     * @param QueryBuilder $queryBuilder Builds the query for data retrieval.
     * @param string $email Email string being used as search criteria.
     * @return bool Returns true if the user exists and false if they don't.
     */
    public static function checkUserExists(QueryBuilder $queryBuilder, string $email): bool
    {
        $exists = true;

        $queryBuilder = $queryBuilder->select('user_id')
            ->from('users', 'u')
            ->where('email = '.$queryBuilder->createNamedParameter($email));

        $query = $queryBuilder->executeQuery();
        $result = $query->fetchOne();

        if ($result == false) {
            $exists = false;
        }

        return $exists;
    }

    /**
     * A function to query the database to retrieve a user's login credentials by searching using an email.
     * @param QueryBuilder $queryBuilder Builds the query for data retrieval.
     * @param string $email Email string being used as search criteria.
     * @return array Returns the results of the query and user's login details.
     */
    public function getUserLoginCredentials(QueryBuilder $queryBuilder, string $email)
    {
        $store_result = [];

        $queryBuilder = $queryBuilder->select(
            'email',
            'password'
        )
            ->from('users', 'u')->where('email = '.$queryBuilder->createNamedParameter($email))
            ->setParameter('email', $email);

        try {
            $store_result['outcome'] = $queryBuilder->executeQuery();
            $store_result['result'] = $store_result['outcome']->fetchAllAssociative();
            $store_result['sql_query'] = $queryBuilder->getSQL();
        } catch (Exception $ex ) {
            $store_result['outcome'] = false;
            $store_result['sql_query'] = $queryBuilder->getSQL();

            return $store_result;
        }

        return $store_result;
    }

    /**
     * A function to query the database to retrieve a user's phone number by searching using an email.
     * @param QueryBuilder $queryBuilder Builds the query for data retrieval.
     * @param string $email Email string being used as search criteria.
     * @return array Returns the results of the query and user's phone number.
     */
    public function getUserPhoneNumber(QueryBuilder $queryBuilder, string $email)
    {
        $store_result = [];

        $queryBuilder = $queryBuilder->select(
            'phoneNumber'
        )
            ->from('users', 'u')->where('email = '.$queryBuilder->createNamedParameter($email))
            ->setParameter('email', $email);

        try {
            $store_result['outcome'] = $queryBuilder->executeQuery();
            $store_result['result'] = $store_result['outcome']->fetchAllAssociative();
            $store_result['sql_query'] = $queryBuilder->getSQL();
        } catch (Exception $ex ) {
            $store_result['outcome'] = false;
            $store_result['sql_query'] = $queryBuilder->getSQL();

            return $store_result;
        }

        return $store_result;
    }
}
