<?php

namespace Service\Authentication;

use Model\User;

class CookieAuthenticationService implements AuthenticationServiceInterface
{
    public function check(): bool
    {
        return isset($_COOKIE['user_id']);
    }

    public function logout() : void
    {
        $_COOKIE['user_id'] = null;
    }

    public function setUser(int $userId) : void
    {
        setcookie('user_id', $userId);
    }

    public function getCurrentUser() : ?User
    {
        if (isset($this->user)) {
            return $this->user;
        }

        if (!$this->check()) {
            return null;
        }

        $userId = $_COOKIE['user_id'];

        $this->user = User::getOneById($userId);
        return $this->user;
    }

    public function login(string $email, string $password) : bool
    {
        $user = User::getOneByEmail($email);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->getPassword())) {
            return false;
        }

        $_COOKIE['user_id'] = $user->getId();

        return true;
    }
}