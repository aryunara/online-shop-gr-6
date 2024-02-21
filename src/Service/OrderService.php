<?php

namespace Service;

use Model\Model;
use Model\Order;
use Model\OrderedProduct;
use Model\Product;
use Model\UserProduct;
use Throwable;

class OrderService
{
    public function create(int $userId, string $name, string $phone, string $email, string $address, string $comment): void
    {
        $pdo = Model::getPdo();
        $products = Product::getProducts($userId);
        $userProducts = UserProduct::getCart($userId);

        $pdo->beginTransaction();
        try {
            $orderId = Order::create($userId, $name, $phone, $email, $address, $comment);

            foreach ($userProducts as $userProduct) {
                $product = $products[$userProduct->getId()];
                $productId = $userProduct->getProductId();
                $quantity = $userProduct->getQuantity();
                $total = $product->getPrice() * $userProduct->getQuantity();

                OrderedProduct::create($orderId, $productId, $quantity, $total);
            }

            $pdo->commit();
        } catch (Throwable $exception) {
            LoggerService::error($exception);

            $pdo->rollBack();
        }
    }
}