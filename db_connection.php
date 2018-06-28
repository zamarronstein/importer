<?php

class DataBase
{
    protected static $connection = NULL;

    public static function get_connection()
    {
        if (self::$connection == null) {

            self::$connection = new mysqli("127.0.0.1", "root", "1q2w3e", "d_izeu");
        }

        if (self::$connection->connect_error) {
            echo self::$connection->connect_error;
            exit();
        }

        return self::$connection;
    }
}