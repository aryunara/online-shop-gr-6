<?php

namespace Controller;

use Model\Order;
use Model\OrderedProduct;
use Model\Product;
use Model\UserProduct;
use Request\OrderRequest;
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
            $total = 0;

            $userProducts = UserProduct::getCart($userId);

            if (!empty($userProducts)) {
                foreach ($userProducts as $userProduct) {
                    $productIds[] = $userProduct->getProductId();
                }

                $products = Product::getAllByIds($productIds);
            }
            require_once './../View/order.phtml';
        }
    }

    public function postOrderPage(OrderRequest $request): void
    {
        $errors = $request->validate();

        if (empty($errors)) {
            $user = $this->sessionAuthenticationService->getCurrentUser();
            if (!$user) {
                header('Location: /login');
            }
            $userId = $user->getId();
            $name = $request->getName();
            $phone = $request->getPhone();
            $email = $request->getEmail();
            $address = $request->getAddress();
            $comment = $request->getComment();

            Order::insertData($userId, $name, $phone, $email, $address, $comment);
//            OrderedProduct::create();

            header('Location: /main');
        }
        require_once './../View/order.phtml';
    }



}