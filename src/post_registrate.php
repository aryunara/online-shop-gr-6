<?php

$mainFlag = true;

$name = $_POST['name'];
if (empty($name)) {
    echo 'Имя должно быть заполнено';
    $mainFlag = false;
}
if (strlen($name) < 2) {
    echo 'Имя должно быть двух символов';
    $mainFlag = false;
}

$email = $_POST['email'];
if (empty($email)) {
    echo 'Почта должна быть заполнена';
    $mainFlag = false;
}
$flag = false;
for ($i = 0; $i < strlen($email); $i++) {
    if ($email[$i] === '@') {
        $flag = true;
    }
}
if ($flag === false) {
    echo 'Почта должна содержать "@"';
    $mainFlag = false;
}

$password = $_POST['psw'];
if (empty($password)) {
    echo 'Пароль должен быть заполнен';
    $mainFlag = false;
}
$passwordRep = $_POST['psw-repeat'];
if (empty($passwordRep)) {
    echo 'Повторите пароль';
    $mainFlag = false;
}
if ($password !== $passwordRep) {
    echo 'Пароли должны совпадать';
    $mainFlag = false;
}

$pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");

if ($mainFlag === true) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
}
//$pdo->exec("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");