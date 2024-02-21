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

    public function getOrderPage(): array
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

        return [
            'view' => 'order.phtml',
            'params' => [
                'userProducts' => $userProducts,
                'products' => $products,
            ],
        ];
    }

    public function postOrderPage(OrderRequest $request): array
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
                $result = $this->orderService->create($userId, $request->getName(), $request->getPhone(), $request->getEmail(), $request->getAddress(), $request->getComment());
                if ($result) {
                    header('Location: /main');
                }
                return [
                    'view' => '500.phtml',
                    'params' => [],
                    ];
            } else {
                header('Location: /cart');
            }
        } else {
            $products = Product::getProducts($userId);

            return [
                'view' => 'order.phtml',
                'params' => [
                    'userProducts' => $userProducts,
                    'products' => $products,
                ],
            ];
        }
    }
}