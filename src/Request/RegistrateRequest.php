<?php

namespace Request;

class RegistrateRequest extends Request
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

    public function validate(): array
    {
        $errors = [];

        if (isset($this->body['name'])) {
            $name = $this->body['name'];
            if (empty($name)) {
                $errors['name'] = 'Имя должно быть заполнено';
            }
            if (strlen($name) < 2) {
                $errors['name'] = 'Имя должно содержать 2 или более символов';
            }
        } else {
            $errors['name'] = 'Поле name не указано';
        }

        if (isset($this->body['email'])) {
            $email = $this->body['email'];
            if (empty($email)) {
                $errors['email'] = 'Почта должна быть заполнена';
            }
            $flag = false;
            for ($i = 0; $i < strlen($email); $i++) {
                if ($email[$i] === '@') {
                    $flag = true;
                }
            }
            if ($flag === false) {
                $errors['email'] = 'Почта должна содержать "@"';
            }
        } else {
            $errors['email'] = 'Поле email не указано';
        }

        if (isset($this->body['psw'])) {
            $password = $this->body['psw'];
            if (empty($password)) {
                $errors['psw'] = 'Пароль должен быть заполнен';
            }
            if (strlen($password) < 3) {
                $errors['psw'] = 'Пароль слишком короткий';
            }
        } else {
            $errors['psw'] = 'Поле password не указано';
        }

        if (isset($this->body['psw-repeat'])) {
            $passwordRep = $this->body['psw-repeat'];
            if (empty($passwordRep)) {
                $errors['psw-repeat'] = 'Повторите пароль';
            }
            if ($password !== $passwordRep) {
                $errors['psw-repeat'] = 'Пароли должны совпадать';
            }
        } else {
            $errors['psw-repeat'] = 'Поле repeat password не указано';
        }
        return $errors;
    }
}