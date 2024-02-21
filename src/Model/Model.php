<?php

namespace Model;
use PDO;

class Model
{
    protected static PDO $pdo;

    public static function init(PDO $pdo): void
    {
        static::$pdo = $pdo;
    }

    public static function getPDO() : PDO
    {
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