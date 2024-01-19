<?php

$errors = [];

if (isset($_POST['name'])) {
    $name = $_POST['name'];
    if (empty($name)) {
        $errors['name'] = 'Имя должно быть заполнено';
    }
    if (strlen($name) < 2) {
        $errors['name'] = 'Имя должно содержать 2 или более символов';
    }
} else {
    $errors['name'] = 'Поле name не указано';
}


if (isset($_POST['email'])) {
    $email = $_POST['email'];
    if (empty($email)) {
        $errors['email'] = 'Почта должна быть заполнена';
    }
    $flag = false;
    for ($i = 0; $i < strlen($email); $i++) {
        if ($email[$i] === '@') {
            $flag = true;
        }
    }
    if ($flag === false) {
        $errors['email'] = 'Почта должна содержать "@"';
    }
} else {
    $errors['email'] = 'Поле email не указано';
}


if (isset($_POST['psw'])) {
    $password = $_POST['psw'];
    if (empty($password)) {
        $errors['psw'] = 'Пароль должен быть заполнен';
    }
    if (strlen($password) < 3) {
        $errors['psw'] = 'Пароль слишком короткий';
    }
} else {
    $errors['psw'] = 'Поле password не указано';
}

if (isset($_POST['psw-repeat'])) {
    $passwordRep = $_POST['psw-repeat'];
    if (empty($passwordRep)) {
        $errors['psw-repeat'] = 'Повторите пароль';
    }
    if ($password !== $passwordRep) {
        $errors['psw-repeat'] = 'Пароли должны совпадать';
    }
} else {
    $errors['psw-repeat'] = 'Поле repeat password не указано';
}


$pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");

if (empty($errors)) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute (['email' => $email]);
    $userInfo = $stmt->fetch();
    print_r($userInfo);
}
else {
    print_r($errors);
}
//$pdo->exec("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");