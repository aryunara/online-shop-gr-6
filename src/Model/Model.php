<?php

namespace Model;
use PDO;

class Model
{
    protected static PDO $pdo;

    protected static function getPdo() : PDO
    {
        self::$pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");

        return self::$pdo;
    }

}