<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\OrderRequest;
use Service\Authentication\AuthenticationServiceInterface;
use Service\OrderService;

class OrderController
{
    private AuthenticationServiceInterface $authenticationService;
    private OrderService $orderService;

    public function __construct(AuthenticationServiceInterface $authenticationService, OrderService $orderService)
    {
        $this->authenticationService = $authenticationService;
        $this->orderService = $orderService;
    }

    public function getOrderPage(): void
    {
        if (!$this->authenticationService->check()) {
            header('Location: /login');
        }

        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $userId = $user->getId();
        $userProducts = UserProduct::getCart($userId);
        $products = Product::getProducts($userId);

        require_once './../View/order.phtml';
    }

    public function postOrderPage(OrderRequest $request): void
    {
        if (!$this->authenticationService->check()) {
            header('Location: /login');
        }

        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $userId = $user->getId();
        $errors = $request->validate();
        $userProducts = UserProduct::getCart($userId);

        if (empty($errors)) {
            if (!empty($userProducts)) {
                $this->orderService->create($userId, $request->getName(), $request->getPhone(), $request->getEmail(), $request->getAddress(), $request->getComment());

                header('Location: /main');
            } else {
                header('Location: /cart');
            }
        } else {
            $products = Product::getProducts($userId);

            require_once './../View/order.phtml';
        }
    }
}