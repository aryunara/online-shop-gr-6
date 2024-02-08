<?php

namespace Model;
class UserProduct extends Model
{
    private int $id;
    private int $user_id;
    private int $product_id;
    private int $quantity;

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public static function getCart($userId): ?array
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM user_products WHERE user_id = :userId');
        $stmt->execute(['userId' => $userId]);
        $cart = $stmt->fetchAll();

        foreach ($cart as $productInCart) {
            $data[] = new UserProduct($productInCart['id'], $productInCart['user_id'], $productInCart['product_id'], $productInCart['quantity']);
        }
        if (empty($data)) {
            return null;
        }

        return $data;
    }

    public static function getUserProductInfo($productId, $userId): ?UserProduct
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }

        return new UserProduct($data['id'], $data['user_id'], $data['product_id'], $data['quantity']);
    }

    public static function getUserProductQuantity($productId, $userId)
    {
        $stmt = self::getPdo()->prepare('SELECT quantity FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }

        return $data['quantity'];
    }

    public static function create(int $userId, int $productId, int $quantity) : void
    {
        $stmt = self::getPdo()->prepare("INSERT INTO user_products (user_id, product_id, quantity) VALUES (:userId, :productId, :quantity)");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'quantity' => $quantity]);
    }

    public static function deleteProduct($productId, $userId): void
    {
        $stmt = self::getPdo()->prepare('DELETE FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
    }


    public function save($quantity, $productId, $userId): void
    {
        $sql = 'UPDATE user_products SET quantity = :quantity WHERE product_id = :productId AND user_id = :userId ';

        $stmt = static::getPdo()->prepare($sql);
        $stmt->execute(['quantity' => $quantity, 'productId' => $productId, 'userId' => $userId]);
    }

}