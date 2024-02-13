<?php

namespace Controller;

use Model\Product;
use Service\Authentication\SessionAuthenticationService;
use Service\CartService;

class ProductController
{
    private SessionAuthenticationService $sessionAuthenticationService;
    private CartService $cartService;

    public function __construct(SessionAuthenticationService $sessionAuthenticationService, CartService $cartService)
    {
        $this->sessionAuthenticationService = $sessionAuthenticationService;
        $this->cartService = $cartService;
    }

    public function getCatalog(): void
    {
        if (!$this->sessionAuthenticationService->check()) {
            header('Location: /login');
        }

        $user = $this->sessionAuthenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $userId = $user->getId();
        $products = Product::getAll();
        $productsCount = $this->cartService->getCount($userId);

        require_once './../View/catalog.phtml';
    }

}

