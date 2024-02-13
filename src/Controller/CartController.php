<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\MinusProductRequest;
use Request\PlusProductRequest;
use Request\RemoveProductRequest;
use Service\CartService;
use Service\SessionAuthenticationService;

class CartController
{
    private SessionAuthenticationService $sessionAuthenticationService;
    private CartService $cartService;

    public function __construct(SessionAuthenticationService $sessionAuthenticationService, CartService $cartService)
    {
        $this->sessionAuthenticationService = $sessionAuthenticationService;
        $this->cartService = $cartService;
    }

    public function getCartProducts(): void
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

        require_once './../View/cart.phtml';
    }

    public function plus(PlusProductRequest $request): void
    {
        if (!$this->sessionAuthenticationService->check()) {
            header('Location: /login');
        }

        $user = $this->sessionAuthenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $this->cartService->plus($request->getId(), $user);

        header('Location: /main');
    }

    public function minus(MinusProductRequest $request): void
    {
        if (!$this->sessionAuthenticationService->check()) {
            header('Location: /login');
        }

        $user = $this->sessionAuthenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $this->cartService->minus($request->getId(), $user);

        header('Location: /main');
    }

    public function removeProductFromCart(RemoveProductRequest $request): void
    {
        $userId = $request->getUserId();
        $productId = $request->getProductId();

        UserProduct::deleteProduct($productId, $userId);

        header('Location: /cart');
    }
}