<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Service\SessionAuthenticationService;

class OrderController
{
    private SessionAuthenticationService $sessionAuthenticationService;
    public function __construct(SessionAuthenticationService $sessionAuthenticationService)
    {
        $this->sessionAuthenticationService = $sessionAuthenticationService;
    }

    public function getOrderPage(): void
    {
        if (!($this->sessionAuthenticationService->check())) {
            header('Location: /login');
        } else {
            $user = $this->sessionAuthenticationService->getCurrentUser();
            if (!$user) {
                header('Location: /login');
            }
            $userId = $user->getId();

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

}