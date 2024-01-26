<?php

require './../Model/Model.php';
class User extends Model
{
    public function insertData(string $name, string $email, string $hash) : void
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :hash)");
        $stmt->execute(['name' => $name, 'email' => $email, 'hash' => $hash]);
    }

    public function getData($email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
}