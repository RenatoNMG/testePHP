<?php

class Database
{
    public static function getConnection()
    {

        $host = 'db';
        $db_name = 'recrutamento';
        $username = 'root';
        $password = '';
        $conn = null;

        try {
            $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            echo "erro com a conexão:  " . $exception->getMessage();
        }
        return $conn;
    }
}
