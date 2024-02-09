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
        session_start();
        if (!($this->sessionAuthenticationService->check())) {
            header('Location: /login');
        } else {
            $user = $this->sessionAuthenticationService->getCurrentUser();
            if (!$user) {
                header('Location: /login');
            }
            $userId = $user->getId();

            $userProducts = UserProduct::getCart($userId);
            $total = 0;

            if (!empty($userProducts)) {
                foreach ($userProducts as $userProduct) {
                    $productIds[] = $userProduct->getProductId();
                }

                $products = Product::getAllByIds($productIds);
            }
            require_once './../View/order.phtml';
        }
    }

}