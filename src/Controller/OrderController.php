<?php

namespace Controller;

use Model\UserProduct;
use Request\OrderRequest;
use Service\CartService;
use Service\OrderService;
use Service\SessionAuthenticationService;

class OrderController
{
    private SessionAuthenticationService $sessionAuthenticationService;
    private CartService $cartService;
    private OrderService $orderService;

    public function __construct(SessionAuthenticationService $sessionAuthenticationService, CartService $cartService, OrderService $orderService)
    {
        $this->sessionAuthenticationService = $sessionAuthenticationService;
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function getOrderPage(): void
    {
        if (!$this->sessionAuthenticationService->check()) {
            header('Location: /login');
        }

        $user = $this->sessionAuthenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $userId = $user->getId();
        $userProducts = UserProduct::getCart($userId);
        $products = $this->cartService->getProducts($userId);

        require_once './../View/order.phtml';
    }

    public function postOrderPage(OrderRequest $request): void
    {
        if (!$this->sessionAuthenticationService->check()) {
            header('Location: /login');
        }

        $user = $this->sessionAuthenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $userId = $user->getId();
        $errors = $request->validate();
        $userProducts = UserProduct::getCart($userId);

        if (empty($errors)) {
            if (!empty($userProducts)) {
                $this->orderService->create($userId, $request->getName(), $request->getPhone(), $request->getEmail(), $request->getAddress(), $request->getComment(), $userProducts);

                header('Location: /main');
            } else {
                header('Location: /cart');
            }
        } else {
            $products = $this->cartService->getProducts($userId);

            require_once './../View/order.phtml';
        }
    }
}