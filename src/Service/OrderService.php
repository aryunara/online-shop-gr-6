<?php

namespace Service;

use Model\Model;
use Model\Order;
use Model\OrderedProduct;
use Model\Product;
use Model\UserProduct;
use PDO;
use Throwable;

class OrderService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $userId, string $name, string $phone, string $email, string $address, string $comment): bool
    {
        $products = Product::getProducts($userId);
        $userProducts = UserProduct::getCart($userId);

        $this->pdo->beginTransaction();
        try {
            $orderId = Order::create($userId, $name, $phone, $email, $address, $comment);

            foreach ($userProducts as $userProduct) {
                $product = $products[$userProduct->getId()];
                $productId = $userProduct->getProductId();
                $quantity = $userProduct->getQuantity();
                $total = $product->getPrice() * $userProduct->getQuantity();

                OrderedProduct::create($orderId, $productId, $quantity, $total);
            }

            $this->pdo->commit();

            return true;
        } catch (Throwable $exception) {
            LoggerService::error($exception);

            $this->pdo->rollBack();

            return false;
        }
    }
}