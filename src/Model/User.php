<?php

namespace Model;
class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;

    public function __construct(int $id, string $name, string $email, string $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public static function insertData(string $name, string $email, string $hash) : void
    {
        $stmt = self::getPdo()->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :hash)");
        $stmt->execute(['name' => $name, 'email' => $email, 'hash' => $hash]);
    }

    public static function getOneByEmail($email)
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }

        return new User($data['id'], $data['name'], $data['email'], $data['password']);
    }
}