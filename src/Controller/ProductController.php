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

    public function getCartProducts(): void
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

            $cart = UserProduct::getCart($userId);
            $total = 0;

            if (!empty($cart)) {
                foreach ($cart as $productInCart) {
                    $productId = $productInCart->getProductId();
                    $productInfo = Product::getOneById($productId);
                    $productsInfo[] = $productInfo;
                }
            }
            require_once './../View/cart.phtml';
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

    public function plus(PlusProductRequest $request)
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

        $product = UserProduct::getProductInCartInfo($productId, $userId);

        if (isset($product)) {
            $product->setQuantity($product->getQuantity() + 1);
            $quantity = $product->getQuantity();
            $product->save($quantity, $productId, $userId);
        } else {
            $quantity = 1;
            UserProduct::create($userId, $productId, $quantity);
        }
        header('Location: /main');
    }

    public function minus(MinusProductRequest $request)
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

        $product = UserProduct::getProductInCartInfo($productId, $userId);

        if (isset($product)) {
            $product->setQuantity($product->getQuantity() - 1);
            if ($product->getQuantity() < 1) {
                UserProduct::deleteProduct($productId, $userId);
            } else {
            $quantity = $product->getQuantity();
            $product->save($quantity, $productId, $userId);
            }
        }
        header('Location: /main');
    }

    public function getProductQuantity($productInfo)
    {
        $productId = $productInfo->getId();

        $user = $this->sessionAuthenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }
        $userId = $user->getId();

        $productInCartInfo = userProduct::getProductInCartInfo($productId, $userId);
        if (empty($productInCartInfo)) {
            return 0;
        } else {
            return $productInCartInfo->getQuantity();
        }
    }

    public function removeProductFromCart(RemoveProductRequest $request): void
    {
        $userId = $request->getUserId();
        $productId = $request->getProductId();

        UserProduct::deleteProduct($productId, $userId);

        header('Location: /cart');
    }

}

