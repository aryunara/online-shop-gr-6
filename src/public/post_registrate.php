<?php

function validate(array $userInfo) : array
{
    $errors = [];

    if (isset($userInfo['name'])) {
        $name = $userInfo['name'];
        if (empty($name)) {
            $errors['name'] = 'Имя должно быть заполнено';
        }
        if (strlen($name) < 2) {
            $errors['name'] = 'Имя должно содержать 2 или более символов';
        }
    } else {
        $errors['name'] = 'Поле name не указано';
    }

    if (isset($userInfo['email'])) {
        $email = $userInfo['email'];
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

    if (isset($userInfo['psw'])) {
        $password = $userInfo['psw'];
        if (empty($password)) {
            $errors['psw'] = 'Пароль должен быть заполнен';
        }
        if (strlen($password) < 3) {
            $errors['psw'] = 'Пароль слишком короткий';
        }
    } else {
        $errors['psw'] = 'Поле password не указано';
    }

    if (isset($userInfo['psw-repeat'])) {
        $passwordRep = $userInfo['psw-repeat'];
        if (empty($passwordRep)) {
            $errors['psw-repeat'] = 'Повторите пароль';
        }
        if ($password !== $passwordRep) {
            $errors['psw-repeat'] = 'Пароли должны совпадать';
        }
    } else {
        $errors['psw-repeat'] = 'Поле repeat password не указано';
    }
    return $errors;
}

$errors = validate($_POST);

if (empty($errors)) {
    $pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['psw'];
    $passwordRep = $_POST['psw-repeat'];

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :hash)");
    $stmt->execute(['name' => $name, 'email' => $email, 'hash' => $hash]);

    header('Location: /get_login.php');
}

require_once './get_registrate.php';
?>
