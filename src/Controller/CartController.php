<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\MinusProductRequest;
use Request\PlusProductRequest;
use Request\RemoveProductRequest;
use Service\Authentication\AuthenticationServiceInterface;

class CartController
{
    private AuthenticationServiceInterface $authenticationService;

    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function getCartProducts(): array
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
            'view' => 'cart.phtml',
            'params' => [
                'user' => $user,
                'userProducts' => $userProducts,
                'products' => $products,
            ],
        ];
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
        $userProduct = UserProduct::getUserProduct($request->getProductId(), $request->getUserId());
        $userProduct->destroy();

        header('Location: /cart');
    }
}