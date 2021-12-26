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

namespace Encryption;

use Ramsey\Uuid\Uuid;

class DoctrineSqlQueries
{
    public function __construct(){}

    public function __destruct(){}

//    public static function queryStoreUserData($queryBuilder, array $cleaned_parameters, string $hashed_password)
//    {
//        $store_result = [];
//        $username = $cleaned_parameters['sanitised_username'];
//        $email = $cleaned_parameters['sanitised_email'];
//        $dietary_requirements = $cleaned_parameters['sanitised_requirements'];
//        var_dump($username);
//        $queryBuilder = $queryBuilder->insert('user_data')
//            ->values([
//                'name' => ":name",
//                'email' => ':email',
//                'diet' => ':diet',
//                'password' => ':password',
//            ])
//            ->setParameters([
//                'name' => $username,
//                'email' => $email,
//                'diet' => $dietary_requirements,
//                'password' => $hashed_password
//            ]);
//        var_dump($queryBuilder->getSQL());
//        $store_result['outcome'] = $queryBuilder->execute();
//        $store_result['sql_query'] = $queryBuilder->getSQL();
//
//        return $store_result;
//    }


//    public static function insertMessageData($queryBuilder, array $cleaned_parameters)
//    {
//        $store_result = [];
//
//        $message_id = Uuid::uuid4();
//        $source = $cleaned_parameters['source'];
//        $destination = $cleaned_parameters['destination'];
//        $message_received_time = $cleaned_parameters['received'];
//        $bearer = $cleaned_parameters['bearer'];
//        $message_ref = $cleaned_parameters['ref'];
//        $switch1 = $cleaned_parameters['switchOne'];
//        $switch2 = $cleaned_parameters['switchTwo'];
//        $switch3 = $cleaned_parameters['switchThree'];
//        $switch4 = $cleaned_parameters['switchFour'];
//        $fan = $cleaned_parameters['fan'];
//        $temperature = $cleaned_parameters['temperature'];
//        $keypad = $cleaned_parameters['keypad'];
//
//        $queryBuilder = $queryBuilder->insert('mobile_numbers')
//            ->values([
//            ])
//            ->setParameters([
//                'name' => $username,
//                'email' => $email,
//                'diet' => $dietary_requirements,
//                'password' => $hashed_password
//            ]);
//        var_dump($queryBuilder->getSQL());
//        $store_result['outcome'] = $queryBuilder->execute();
//        $store_result['sql_query'] = $queryBuilder->getSQL();
//
//        return $store_result;
//    }

    public static function checkMobileExists($queryBuilder, $mobile_number) {
        $store_result = [];

        $queryBuilder = $queryBuilder->select('m')
            ->from('mobile_numbers', 'm')
            ->where('m.mobile_number = :identifier')
            ->setParameter(':identifier', $mobile_number);

        $store_result['outcome'] = $queryBuilder->execute();
        $store_result['sql_query'] = $queryBuilder->getSQL();
    }

//    public static function queryRetrieveUserData($queryBuilder, array $cleaned_parameters)
//    {
//        $retrieve_result = [];
//        $username = $cleaned_parameters['sanitised_username'];
//
//        $queryBuilder
//            ->select('password', 'email')
//            ->from('user_data', 'u')
//            ->where('name = ' .  $queryBuilder->createNamedParameter($username));
//
//        $query = $queryBuilder->execute();
//        $result = $query->fetchAll();
//
//        return $result;
//    }
}
