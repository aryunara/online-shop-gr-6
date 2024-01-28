<?php

require './../Model/Model.php';
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

}