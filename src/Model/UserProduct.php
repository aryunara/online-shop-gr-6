<?php

namespace Model;
class UserProduct extends Model
{
    public function create(int $userId, int $productId, int $quantity) : void
    {
        $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, quantity) VALUES (:userId, :productId, :quantity)");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'quantity' => $quantity]);
    }

    public function getCart($user_id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM user_products WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function getProductInCartInfo($productInCart)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM products WHERE id = :productInCart');
        $stmt->execute(['productInCart' => $productInCart]);
        return $stmt->fetchAll();
    }

    public function deleteProduct($productId, $userId)
    {
        $stmt = $this->pdo->prepare('DELETE FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
    }

    public function getQuantity($productId, $userId)
    {
        $stmt = $this->pdo->prepare('SELECT quantity FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        return $stmt->fetch();
    }

}