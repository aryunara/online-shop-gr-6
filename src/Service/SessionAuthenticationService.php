<?php

namespace Service;

use Model\User;
class SessionAuthenticationService
{
    private User $user;
    public function check(): bool
    {
        self::startSession();

        return isset($_SESSION['user_id']);
    }

    public function logout() : void
    {
        self::startSession();

        $_SESSION['user_id'] = null;
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

        $this->startSession();
        $_SESSION['user_id'] = $user->getId();

        return true;
    }

    public function getCurrentUser() : ?User
    {
        if (isset($this->user)) {
            return $this->user;
        }

        if (!$this->check()) {
            return null;
        }
        $this->startSession();
        $userId = $_SESSION['user_id'];

        $this->user = User::getOneById($userId);
        return $this->user;
    }

    public function startSession() : void
    {
        if (session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }
    }

}