<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\MinusProductRequest;
use Request\PlusProductRequest;
use Request\RemoveProductRequest;
use Service\Authentication\AuthenticationServiceInterface;
use Service\CartService;

class CartController
{
    private AuthenticationServiceInterface $authenticationService;

    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function getCartProducts(): void
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

        require_once './../View/cart.phtml';
    }

    public function plus(PlusProductRequest $request): void
    {
        if (!$this->authenticationService->check()) {
            header('Location: /login');
        }

        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $userProduct = UserProduct::getUserProduct($request->getId(), $user->getId());
        if (isset($userProduct)) {
            $userProduct->incrementQuantity();
        } else {
            UserProduct::create($user->getId(), $request->getId(), 1);
        }



        header('Location: /main');
    }

    public function minus(MinusProductRequest $request): void
    {
        if (!$this->authenticationService->check()) {
            header('Location: /login');
        }

        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $userProduct = UserProduct::getUserProduct($request->getId(), $user->getId());
        $userProduct->decrementQuantity();

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