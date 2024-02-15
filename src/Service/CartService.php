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
                $userProduct->destroy();
            } else {
                $userProduct->save();
            }
        }
    }

    public function getCount($userId): int
    {
        $userProducts = UserProduct::getCart($userId);
        if (empty($userProducts)) {
            return 0;
        }

        $sum = 0;
        foreach ($userProducts as $userProduct) {
            $sum += $userProduct->getQuantity();
        }

        return $sum;
    }
}