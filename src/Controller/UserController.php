<?php

namespace Controller;

use Model\User;
use PDOException;
use Request\LoginRequest;
use Request\RegistrateRequest;
use Service\SessionAuthenticationService;

class UserController
{
    private SessionAuthenticationService $sessionAuthenticationService;
    public function __construct(SessionAuthenticationService $sessionAuthenticationService)
    {
        $this->sessionAuthenticationService = $sessionAuthenticationService;
    }
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

            $hash = password_hash($password, PASSWORD_DEFAULT);
            try {
                User::create($name, $email, $hash);

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
        $errors = $request->validate();

        if (empty($errors)) {

            $email = $request->getEmail();
            $password = $request->getPassword();

            $result = $this->sessionAuthenticationService->login($email, $password);

            if ($result) {
                header('Location: /main');
            } else {
                $errors['email'] = "Неверный пароль или email";
            }
        }
        require_once './../View/get_login.phtml';
    }

    public function logout(): void
    {
        $this->sessionAuthenticationService->logout();

        header('Location: /login');
    }
}