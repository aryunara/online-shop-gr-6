<?php

namespace Controller;

use Model\User;
use Request\LoginRequest;
use Request\RegistrateRequest;
use Request\Request;

class UserController
{
    public function getRegistrate(): void
    {
        require_once './../View/get_registrate.phtml';
    }

    public function postRegistrate(RegistrateRequest $request): void
    {
        $errors = $request->validate();

        if (empty($errors)) {
            $name = $request->getName();
            $email = $request->getEmail();
            $password = $request->getPassword();
            $passwordRep = $request->getPassword();

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

    public function getLogin(): void
    {
        require_once './../View/get_login.phtml';
    }

    public function postLogin(LoginRequest $request): void
    {

        $errors = [];

        if (isset($this->body['email'])) {
            $email = $this->body['email'];
        }
        if (isset($this->body['psw'])) {
            $password = $this->body['psw'];
        }

        $email = $request->getEmail();
        $password = $request->getPassword();

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