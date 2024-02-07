<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;

class OrderController
{
    public function getOrderPage(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        } else {
            $userId = $_SESSION['user_id'];

            $cart = UserProduct::getCart($userId);
            $total = 0;

            if (!empty($cart)) {
                foreach ($cart as $productInCart) {
                    $productId = $productInCart->getProductId();
                    $productInfo = Product::getOneById($productId);
                    $productsInfo[] = $productInfo;
                }
            }
            require_once './../View/order.phtml';
        }
    }

    public function getProductQuantity($productInfo)
    {
        $productId = $productInfo->getId();
        $userId = $_SESSION['user_id'];

        $productInCartInfo = userProduct::getProductInCartInfo($productId, $userId);
        if (empty($productInCartInfo)) {
            return 0;
        } else {
            return $productInCartInfo->getQuantity();
        }
    }
}