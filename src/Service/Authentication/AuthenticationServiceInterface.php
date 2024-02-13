<?php

namespace Service\Authentication;

use Model\User;

interface AuthenticationServiceInterface
{
    public function check(): bool;

    public function login(string $email, string $password) : bool;

    public function logout() : void;

    public function getCurrentUser() : ?User;
}