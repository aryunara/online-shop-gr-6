<?php

namespace Model;
class UserProduct extends Model
{
    private int $id;
    private int $user_id;
    private int $product_id;
    private int $quantity;

    public function __construct(int $id, int $user_id, int $product_id, int $quantity)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public static function create(int $userId, int $productId, int $quantity) : void
    {
        $stmt = self::getPdo()->prepare("INSERT INTO user_products (user_id, product_id, quantity) VALUES (:userId, :productId, :quantity)");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'quantity' => $quantity]);
    }

    public static function getCart($user_id): false|array
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM user_products WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public static function deleteProduct($productId, $userId): void
    {
        $stmt = self::getPdo()->prepare('DELETE FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
    }

    public static function getProductInCartInfo($productId, $userId)
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }

        return new UserProduct($data['id'], $data['user_id'], $data['product_id'], $data['quantity']);
    }

}