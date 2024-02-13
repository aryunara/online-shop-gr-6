<?php

namespace Service;

use Model\Order;
use Model\OrderedProduct;
use Model\UserProduct;

class OrderService
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function create(int $userId, string $name, string $phone, string $email, string $address, string $comment, array $userProducts): void
    {
        $orderId = Order::create($userId, $name, $phone, $email, $address, $comment);

        $products = $this->cartService->getProducts($userId);

        foreach ($userProducts as $userProduct) {
            $product = $products[$userProduct->getProductId()];
            $productId = $product->getId();
            $quantity = $userProduct->getQuantity();
            $total = $product->getPrice() * $userProduct->getQuantity();

            OrderedProduct::create($orderId, $productId, $quantity, $total);
        }
    }
}