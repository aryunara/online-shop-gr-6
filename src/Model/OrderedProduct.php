<?php

namespace Model;

class OrderedProduct extends Model
{
    public static function create(int $orderId, int $productId, int $quantity, float $total) : void
    {
        $stmt = self::getPdo()->prepare("INSERT INTO ordered_products (order_id, product_id, quantity, total_price) VALUES (:orderId, :productId, :quantity, :total)");
        $stmt->execute(['orderId' => $orderId, 'productId' => $productId, 'quantity' => $quantity, 'total' => $total]);
    }

}