<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\MinusProductRequest;
use Request\PlusProductRequest;
use Request\RemoveProductRequest;

class CartController extends MainController
{
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

        return $this->viewRenderer->render('cart.phtml', ['user' => $user, 'userProducts' => $userProducts, 'products' => $products], false);
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

        $errors = $request->validate();

        if (empty($errors)) {
            $userProduct = UserProduct::getUserProduct($request->getId(), $user->getId());
            if (isset($userProduct)) {
                $userProduct->incrementQuantity();
            } else {
                UserProduct::create($user->getId(), $request->getId(), 1);
            }

            header('Location: /main');
        }
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

        $errors = $request->validate();

        if (empty($errors)) {
            $userProduct = UserProduct::getUserProduct($request->getId(), $user->getId());
            $userProduct->decrementQuantity();

            header('Location: /main');
        }
    }

    public function removeProductFromCart(RemoveProductRequest $request): void
    {
        $userProduct = UserProduct::getUserProduct($request->getProductId(), $request->getUserId());
        $userProduct->destroy();

        header('Location: /cart');
    }
}