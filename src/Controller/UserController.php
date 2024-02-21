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
    public function getRegistrate(): array
    {
        return [
            'view' => 'get_registrate.phtml',
            'params' => [],
        ];
    }

    public function postRegistrate(RegistrateRequest $request): array
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

        return [
            'view' => 'get_registrate.phtml',
            'params' => [
                'errors' => $errors,
            ],
        ];
    }

    public function getLogin(): array
    {
        return [
            'view' => 'get_login.phtml',
            'params' => [],
        ];
    }

    public function postLogin(LoginRequest $request): array
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

        return [
          'view' => 'get_login.phtml',
          'params' => [
              'errors' => $errors,
          ],
        ];
    }

    public function logout(): void
    {
        $this->authenticationService->logout();

        header('Location: /login');
    }
}