<?php

namespace Model;

class Order extends Model
{

    private int $orderId;
    private int $userId;
    private string $userName;
    private string $phone;
    private string $email;
    private string $address;
    private string $comment;

    public function __construct(int $orderId, int $userId, string $userName, string $phone, string $email, string $address, string $comment)
    {
        $this->orderId = $orderId;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->phone = $phone;
        $this->email = $email;
        $this->address = $address;
        $this->comment = $comment;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public static function create(int $userId, string $name, string $phone, string $email, string $address, string $comment = null) : void
    {
        $stmt = self::getPdo()->prepare("INSERT INTO orders (user_id, user_name, phone, email, address, comment) VALUES (:userId, :name, :phone, :email, :address, :comment)");
        $stmt->execute(['userId' => $userId, 'name' => $name, 'phone' => $phone, 'email' => $email, 'address' => $address, 'comment' => $comment]);
    }

    public static function getLastByUserId(int $userId): ?Order
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM orders WHERE user_id = :userId ORDER BY order_id DESC LIMIT 1');
        $stmt->execute(['userId' => $userId]);
        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }

        return new Order($data['order_id'], $data['user_id'], $data['user_name'], $data['phone'], $data['email'], $data['address'], $data['comment']);
    }

}