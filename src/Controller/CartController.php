<?php

namespace Controller;

use Core\ViewRenderer;
use Model\Product;
use Model\UserProduct;
use Request\MinusProductRequest;
use Request\PlusProductRequest;
use Request\RemoveProductRequest;
use Service\Authentication\AuthenticationServiceInterface;

class CartController
{
    private AuthenticationServiceInterface $authenticationService;
    private ViewRenderer $viewRenderer;

    public function __construct(AuthenticationServiceInterface $authenticationService, ViewRenderer $viewRenderer)
    {
        $this->authenticationService = $authenticationService;
        $this->viewRenderer = $viewRenderer;
    }

    public function getCartProducts(): string
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

        return $this->viewRenderer->render('cart.phtml', ['user' => $user, 'userProducts' => $userProducts, 'products' => $products]);

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