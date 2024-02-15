<?php

namespace Model;
use PDO;

class Model
{
    protected static PDO $pdo;

    public static function getPdo() : PDO
    {
        self::$pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");

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