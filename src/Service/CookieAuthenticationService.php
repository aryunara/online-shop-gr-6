<?php

namespace Service;

class CookieAuthenticationService extends SessionAuthenticationService
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
        self::startSession();

        setcookie('user_id', $userId);
    }
}