<?php

class Database
{
    /**
     * @var PDO|null
     */
    private static  $instance = null;

    /**
     * @return PDO
     */
    public static function getConnection()
    {
        if (!self::$instance) {
            $db = parse_ini_file(__DIR__ . '/../config/config.ini', true)['database'];

            $dsn = sprintf(
                "%s:host=%s;port=%s;dbname=%s;charset=%s",
                $db['driver'],
                $db['host'],
                $db['port'],
                $db['dbname'],
                $db['charset']
            );


            self::$instance = new PDO(
                $dsn,
                $db['username'],
                $db['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }

        return self::$instance;
    }
}
