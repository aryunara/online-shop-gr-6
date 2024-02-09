<?php

namespace Model;

class Order extends Model
{
    public static function insertData(int $userId, string $name, string $phone, string $email, string $address, string $comment = null) : void
    {
        $stmt = self::getPdo()->prepare("INSERT INTO orders (user_id, user_name, phone, email, address, comment) VALUES (:userId, :name, :phone, :email, :address, :comment)");
        $stmt->execute(['userId' => $userId, 'name' => $name, 'phone' => $phone, 'email' => $email, 'address' => $address, 'comment' => $comment]);
    }

}