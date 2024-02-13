<?php

namespace Service\Authentication;

use Model\User;

class SessionAuthenticationService implements AuthenticationServiceInterface
{
    private User $user;

    public function startSession() : void
    {
        if (session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }
    }

    public function check(): bool
    {
        self::startSession();

        return isset($_SESSION['user_id']);
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

        self::startSession();
        $_SESSION['user_id'] = $user->getId();

        return true;
    }

    public function logout() : void
    {
        self::startSession();

        session_destroy();
    }

    public function getCurrentUser() : ?User
    {
        if (isset($this->user)) {
            return $this->user;
        }

        if (!$this->check()) {
            return null;
        }
        self::startSession();
        $userId = $_SESSION['user_id'];

        $this->user = User::getOneById($userId);
        return $this->user;
    }

}