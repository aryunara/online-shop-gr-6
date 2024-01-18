<?php

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['psw'];
$passwordRep = $_POST['psw-repeat'];


$pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");

$pdo->exec("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");