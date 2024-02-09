<?php

namespace Model;

class OrderedProduct extends Model
{
    public static function create(int $userId, int $productId, int $quantity) : void
    {
        $stmt = self::getPdo()->prepare("INSERT INTO user_products (user_id, product_id, quantity) VALUES (:userId, :productId, :quantity)");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'quantity' => $quantity]);
    }

}