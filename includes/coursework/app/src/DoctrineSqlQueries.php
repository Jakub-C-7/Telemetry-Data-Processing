<?php
/**
 * class to contain all database access using Doctrine's QueryBulder
 *
 * A QueryBuilder provides an API that is designed for conditionally constructing a DQL query in several steps.
 *
 * It provides a set of classes and methods that is able to programmatically build queries, and also provides
 * a fluent API.
 * This means that you can change between one methodology to the other as you want, or just pick a preferred one.
 *
 * From https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/query-builder.html
 */

namespace Coursework;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Query;
use Ramsey\Uuid\Uuid;

class DoctrineSqlQueries
{
    public function __construct(){}

    public function __destruct(){}

    /**
     * A function to insert mobile numbers into the mobile_numbers table.
     * @param $queryBuilder QueryBuilder builds the command to insert data.
     * @param $mobile_number string The mobile number to insert.
     * @return array Returns information about how the transaction went.
     */
    public static function insertMobileNumber($queryBuilder, $mobile_number)
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
    public static function checkMobileNumberExists(QueryBuilder $queryBuilder, string $mobile_number): bool {
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
    public static function insertMessageData(QueryBuilder $queryBuilder, array $cleaned_parameters)
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
    public static function checkMessageExists(QueryBuilder $queryBuilder, string $sender, string $recipient, string $dateTimeReceived): bool {
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
}
