<?php

namespace Controller;

use Model\User;

class UserController
{
    public function getRegistrate(): void
    {
        require_once './../View/get_registrate.phtml';
    }

    public function postRegistrate(): void
    {
        $errors = $this->validate($_POST);

        if (empty($errors)) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['psw'];
            $passwordRep = $_POST['psw-repeat'];

            $hash = password_hash($password, PASSWORD_DEFAULT);
            try {

                User::insertData($name, $email, $hash);

                header('Location: /login');
            } catch (PDOException){
                $errors['email'] = "Пользователь с таким email уже существует";
            }

        }
        require_once './../View/get_registrate.phtml';
    }
    private function validate(array $userInfo) : array
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

    public function getLogin(): void
    {
        require_once './../View/get_login.phtml';
    }

    public function postLogin(): void
    {

        $errors = [];

        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        }
        if (isset($_POST['psw'])) {
            $password = $_POST['psw'];
        }

        $user = User::getOneByEmail($email);

        if (empty($user)) {
            $errors['email'] = 'Неверный email';
        } else {
            if (password_verify($password, $user->getPassword())) {
                session_start();
                $_SESSION['user_name'] = $user->getName();
                $_SESSION['user_email'] = $user->getEmail();
                $_SESSION['user_id'] = $user->getId();
                header('Location: /main');
            } else {
                $errors['psw'] = "Неверный пароль";
            }
        }
        require_once './../View/get_login.phtml';
    }

    public function logout(): void
    {
        session_start();

        session_destroy();

        header('Location: /login');
    }
}