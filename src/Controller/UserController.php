<?php

namespace Controller;

use Model\User;
use PDOException;
use Request\LoginRequest;
use Request\RegistrateRequest;
use Service\Authentication\AuthenticationServiceInterface;

class UserController
{
    private AuthenticationServiceInterface $authenticationService;
    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
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

            $result = $this->authenticationService->login($email, $password);

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
        $this->authenticationService->logout();

        header('Location: /login');
    }
}