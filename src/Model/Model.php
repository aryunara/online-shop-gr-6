<?php

namespace Model;
use PDO;

class Model
{
    protected PDO $pdo;
    public function __construct()
    {
        $this->pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");
    }

}