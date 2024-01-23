<?php

$pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");

$pdo->exec('CREATE TABLE test (id serial not null, name varchar (255) not null)');

//$pdo->exec("INSERT INTO test1 (name) VALUES ('Ivan')");
//
//$q = $pdo->query('SELECT * FROM test1');
//
//print_r($q->fetchAll());