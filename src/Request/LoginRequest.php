<?php

namespace Request;

class LoginRequest extends Request
{

    public function getName(): string
    {
        return $this->body['name'];
    }

    public function getEmail(): string
    {
        return $this->body['email'];
    }

    public function getPassword(): string
    {
        return $this->body['psw'];
    }

    public function validate()
    {
        $errors = [];

        if (isset($this->body['email'])) {
            $email = $this->body['email'];
        } else {
            $errors['email'] = 'Поле email не указано';
        }
        if (isset($this->body['psw'])) {
            $password = $this->body['psw'];
        } else {
            $errors['psw'] = 'Поле password не указано';
        }

        return $errors;
    }

}