<?php

namespace Controller\Api;

use JsonException;
use Model\User;
use Request\Api\CreateUserRequest;

class UserController
{
    /**
     * @throws JsonException
     */
    public function index(): ?string
    {
        $users = User::all();

        return json_encode(['users' => $users], JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public function create(CreateUserRequest $request): false|string
    {
        $errors = $request->validate();

        if (empty($errors)) {
            $name = $request->getName();
            $email = $request->getEmail();
            $password = $request->getPassword();

            $user = User::create($name, $email, $password);

            return json_encode($user, JSON_THROW_ON_ERROR);
        }

        return json_encode($errors);
    }
}