<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\MinusProductRequest;
use Request\PlusProductRequest;
use Request\RemoveProductRequest;
use Service\SessionAuthenticationService;

class ProductController
{
    private SessionAuthenticationService $sessionAuthenticationService;
    public function __construct(SessionAuthenticationService $sessionAuthenticationService)
    {
        $this->sessionAuthenticationService = $sessionAuthenticationService;
    }

    public function getCatalog(): void
    {
        if (!($this->sessionAuthenticationService->check())) {
            header('Location: /login');
        } else {
            $user = $this->sessionAuthenticationService->getCurrentUser();
            if (!$user) {
                header('Location: /login');
            }
            $userId = $user->getId();

            $products = Product::getAll();
            $productsCount = $this->countProducts($userId);

            require_once './../View/catalog.phtml';
        }
    }

    public function countProducts($userId): int
    {
        $cart = UserProduct::getCart($userId);
        if (empty($cart)) {
            return 0;
        } else {
            $sum = 0;
            foreach ($cart as $productInCart) {
                $sum += $productInCart->getQuantity();
            }
            return $sum;
        }
    }

    public function plus(PlusProductRequest $request): void
    {
        if (!($this->sessionAuthenticationService->check())) {
            header('Location: /login');
        }
        $productId = $request->getId();

        $user = $this->sessionAuthenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }
        $userId = $user->getId();

        $userProductInfo = UserProduct::getUserProductInfo($productId, $userId);

        if (isset($userProductInfo)) {
            $userProductInfo->setQuantity($userProductInfo->getQuantity() + 1);
            $quantity = $userProductInfo->getQuantity();
            $userProductInfo->save($quantity, $productId, $userId);
        } else {
            $quantity = 1;
            UserProduct::create($userId, $productId, $quantity);
        }
        header('Location: /main');
    }

    public function minus(MinusProductRequest $request): void
    {
        if (!($this->sessionAuthenticationService->check())) {
            header('Location: /login');
        }
        $productId = $request->getId();

        $user = $this->sessionAuthenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }
        $userId = $user->getId();

        $userProductInfo = UserProduct::getUserProductInfo($productId, $userId);

        if (isset($userProductInfo)) {
            $userProductInfo->setQuantity($userProductInfo->getQuantity() - 1);
            if ($userProductInfo->getQuantity() < 1) {
                UserProduct::deleteProduct($productId, $userId);
            } else {
            $quantity = $userProductInfo->getQuantity();
            $userProductInfo->save($quantity, $productId, $userId);
            }
        }
        header('Location: /main');
    }

}

