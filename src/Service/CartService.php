<?php

namespace Service;

use Model\Product;
use Model\User;
use Model\UserProduct;

class CartService
{
    public function plus(int $productId, User $user): void
    {
        $userId = $user->getId();
        $userProduct = UserProduct::getUserProduct($productId, $userId);

        if (isset($userProduct)) {
            $userProduct->setQuantity($userProduct->getQuantity() + 1);
            $userProduct->save();
        } else {
            UserProduct::create($userId, $productId, 1);
        }
    }

    public function minus(int $productId, User $user): void
    {
        $userId = $user->getId();
        $userProduct = UserProduct::getUserProduct($productId, $userId);

        if (isset($userProduct)) {
            $userProduct->setQuantity($userProduct->getQuantity() - 1);
            if ($userProduct->getQuantity() < 1) {
                UserProduct::deleteProduct($productId, $userId);
            } else {
                $userProduct->save();
            }
        }
    }

    public function getProducts(int $userId): ?array
    {
        $userProducts = UserProduct::getCart($userId);
        if (!empty($userProducts)) {
            foreach ($userProducts as $userProduct) {
                $productIds[] = $userProduct->getProductId();
            }
            $products = Product::getAllByIds($productIds);
        }

        return $products ?? null;
    }

    public function countProducts($userId): int
    {
        $cart = UserProduct::getCart($userId);
        if (empty($cart)) {
            return 0;
        } else {
            $sum = 0;
            foreach ($cart as $productInCart) {
                $sum += $productInCart->getQuantity();
            }
            return $sum;
        }
    }
}