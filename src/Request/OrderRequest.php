<?php

namespace Request;

class OrderRequest extends Request
{

    public function getName(): string
    {
        return $this->body['name_input'];
    }

    public function getPhone(): string
    {
        return $this->body['phone_input'];
    }

    public function getEmail(): string
    {
        return $this->body['email_input'];
    }

    public function getAddress() : string
    {
        return $this->body['address_input'];
    }

    public function getComment() : string
    {
        return $this->body['comment_input'];
    }

    public function validate(): array
    {
        $errors = [];

        if (isset($this->body['name_input'])) {
            $name = $this->body['name_input'];
            if (empty($name)) {
                $errors['name_input'] = 'Имя должно быть заполнено';
            }
            if (strlen($name) < 2) {
                $errors['name_input'] = 'Имя должно содержать 2 или более символов';
            }
        } else {
            $errors['name_input'] = 'Поле name не указано';
        }

        if (isset($this->body['email_input'])) {
            $email = $this->body['email_input'];
            if (empty($email)) {
                $errors['email_input'] = 'Почта должна быть заполнена';
            }
            $flag = false;
            for ($i = 0; $i < strlen($email); $i++) {
                if ($email[$i] === '@') {
                    $flag = true;
                }
            }
            if ($flag === false) {
                $errors['email_input'] = 'Почта должна содержать "@"';
            }
        } else {
            $errors['email_input'] = 'Поле email не указано';
        }

        if (isset($this->body['phone_input'])) {
            $phone = $this->body['phone_input'];
            if (empty($phone)) {
                $errors['phone_input'] = 'Телефон должен быть заполнен';
            }
            if (strlen($phone) < 11) {
                $errors['phone_input'] = 'Телефон слишком короткий';
            }
        } else {
            $errors['phone_input'] = 'Поле phone не указано';
        }

        if (isset($this->body['address_input'])) {
            $address = $this->body['address_input'];
            if (empty($address)) {
                $errors['address_input'] = 'Адрес должен быть заполнен';
            }
        } else {
            $errors['address_input'] = 'Поле address не указано';
        }
        return $errors;
    }
}