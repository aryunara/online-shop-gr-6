<?php

namespace Model;
class UserProduct extends Model
{
    public function create(int $userId, int $productId, int $quantity) : void
    {
        $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, quantity) VALUES (:userId, :productId, :quantity)");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'quantity' => $quantity]);
    }

    public function getCart($user_id): false|array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user_products WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function getProductFromCartInfo($productInCartId): false|array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :productInCartId');
        $stmt->execute(['productInCartId' => $productInCartId]);
        return $stmt->fetchAll();
    }

    public function deleteProduct($productId, $userId): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
    }

    public function getProductInCartInfo($productId, $userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        return $stmt->fetch();
    }

}