<?php
namespace App\Config;

use Flight;
use PDO;

class DBConnection
{
    private static ?PDO $connection = null;

    private function __construct() {}

    public static function getConnection() : PDO
    {
        if (self::$connection == null)
        {
            $connectionPath = $_ENV['DB_CONNECTION'];
            $username = $_ENV['DB_USERNAME'];
            $password = $_ENV['DB_PASSWORD'];
            Flight::register('db', 'PDO', array($connectionPath, $username, $password),
                function($db) {
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
                }
            );
            self::$connection = Flight::db();
        }

        return self::$connection;
    }
}