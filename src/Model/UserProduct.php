<?php

require './../Model/Model.php';
class UserProduct extends Model
{
    public function create(int $userId, int $productId, int $quantity) : void
    {
        $stmt = $this->pdo->prepare("INSERT INTO user_products (user_id, product_id, quantity) VALUES (:userId, :productId, :quantity)");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'quantity' => $quantity]);
    }

}