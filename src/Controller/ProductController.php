<?php

namespace Controller;

use Model\Product;
use Service\Authentication\AuthenticationServiceInterface;
use Service\Authentication\SessionAuthenticationService;
use Service\CartService;

class ProductController
{
    private AuthenticationServiceInterface $authenticationService;
    private CartService $cartService;

    public function __construct(AuthenticationServiceInterface $authenticationService, CartService $cartService)
    {
        $this->authenticationService = $authenticationService;
        $this->cartService = $cartService;
    }

    public function getCatalog(): void
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
        $productsCount = $this->cartService->getCount($userId);

        require_once './../View/catalog.phtml';
    }

}

