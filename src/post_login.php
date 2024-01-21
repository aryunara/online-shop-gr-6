<?php

$errors = [];

if (isset($_POST['username'])) {
    $username = $_POST['username'];
}
if (isset($_POST['password'])) {
    $password = $_POST['password'];
}

$pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");

$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :username');
$stmt->execute(['username' => $username]);
$userInfo = $stmt->fetch();

if (empty($userInfo)) {
    $errors['username'] = 'Неверный email';
} else {
    if ($password === $userInfo['password']) {
        echo "Вы залогинились";
    } else {
        $errors['password'] = "Неверный пароль";
    }
}

require_once './get_login.php';




