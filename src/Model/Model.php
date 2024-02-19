<?php

namespace Model;
use PDO;

class Model
{
    protected static PDO $pdo;

    public static function getPdo() : PDO
    {
        $host = getenv('DB_HOST', 'db');
        $db = getenv('DB_DATABASE', 'db');
        $user = getenv('DB_USER', 'aryuna');
        $password = getenv('DB_PASSWORD', '030201');

        self::$pdo = new PDO("pgsql:host=$host; port=5432; dbname=$db", $user, $password);

        return self::$pdo;
    }

    protected static function prepareExecute(string $sql, array $data): false|\PDOStatement
    {
        $stmt = self::getPDO()->prepare($sql);

        foreach ($data as $param => $value)
        {
            $stmt->bindValue(":$param", $value);

        }
        $stmt->execute();

        return $stmt;

    }

}