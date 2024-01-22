<?php

$errors = [];

if (isset($_POST['email'])) {
    $email = $_POST['email'];
}
if (isset($_POST['psw'])) {
    $password = $_POST['psw'];
}

$pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");

$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
$userInfo = $stmt->fetch();

if (empty($userInfo)) {
    $errors['email'] = 'Неверный email';
} else {
    if ($password === $userInfo['password']) {
        header('Location: /main.php');
    } else {
        $errors['psw'] = "Неверный пароль";
    }
}

require_once './get_login.php';




