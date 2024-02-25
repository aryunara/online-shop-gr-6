<?php

namespace Controller;

use Core\ViewRenderer;
use Model\Product;
use Model\UserProduct;
use Service\Authentication\AuthenticationServiceInterface;

class ProductController
{
    private AuthenticationServiceInterface $authenticationService;
    private ViewRenderer $viewRenderer;

    public function __construct(AuthenticationServiceInterface $authenticationService, ViewRenderer $viewRenderer)
    {
        $this->authenticationService = $authenticationService;
        $this->viewRenderer = $viewRenderer;
    }

    public function getCatalog(): string
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

        return $this->viewRenderer->render('catalog.phtml', ['user' => $user, 'products' => $products, 'productsCount' => $productsCount], true);
    }

}

