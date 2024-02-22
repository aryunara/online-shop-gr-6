<?php

namespace Controller;

use Core\ViewRenderer;
use Model\User;
use PDOException;
use Request\LoginRequest;
use Request\RegistrateRequest;
use Service\Authentication\AuthenticationServiceInterface;

class UserController
{
    private AuthenticationServiceInterface $authenticationService;
    private ViewRenderer $viewRenderer;

    public function __construct(AuthenticationServiceInterface $authenticationService, ViewRenderer $viewRenderer)
    {
        $this->authenticationService = $authenticationService;
        $this->viewRenderer = $viewRenderer;
    }

    public function getRegistrate(): string
    {
        return $this->viewRenderer->render('get_registrate.phtml', []);
    }

    public function postRegistrate(RegistrateRequest $request): string
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

        return $this->viewRenderer->render('get_registrate.phtml', ['errors' => $errors]);
    }

    public function getLogin(): string
    {
        return $this->viewRenderer->render('get_login.phtml', []);
    }

    public function postLogin(LoginRequest $request): string
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

        return $this->viewRenderer->render('get_login.phtml', ['errors' => $errors]);
    }

    public function logout(): void
    {
        $this->authenticationService->logout();

        header('Location: /login');
    }
}