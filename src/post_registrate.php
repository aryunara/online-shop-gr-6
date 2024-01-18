<?php

$name = $_POST['name'];
if (empty($name)) {
    echo 'Имя должно быть заполнено';
}
if (strlen($name) < 2) {
    echo 'Имя должно быть двух символов';
}

$email = $_POST['email'];
if (empty($email)) {
    echo 'Почта должна быть заполнена';
}
$flag = false;
for ($i = 0; $i < strlen($email); $i++) {
    if ($email[$i] === '@') {
        $flag = true;
    }
}
if ($flag === false) {
    echo 'Почта должна содержать "@"';
}

$password = $_POST['psw'];
if (empty($password)) {
    echo 'Пароль должен быть заполнен';
}
$passwordRep = $_POST['psw-repeat'];
if (empty($passwordRep)) {
    echo 'Повторите пароль';
}
if ($password !== $passwordRep) {
    echo 'Пароли должны совпадать';
}

$pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");

$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
$stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

//$pdo->exec("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");