<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Service\Authentication\AuthenticationServiceInterface;

class ProductController
{
    private AuthenticationServiceInterface $authenticationService;

    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function getCatalog(): array
    {
        if (!$this->authenticationService->check()) {
            header('Location: /login');
        }

        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $userId = $user->getId();
        $products = Product::getAll();
        $productsCount = UserProduct::getCount($userId);

        return [
            'view' => 'catalog.phtml',
            'params' => [
                'user' => $user,
                'products' => $products,
                'productsCount' => $productsCount,
            ],
        ];
    }

}

