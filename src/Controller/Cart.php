<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\RemoveProductRequest;
use Service\SessionAuthenticationService;

class Cart
{
    private SessionAuthenticationService $sessionAuthenticationService;
    public function __construct(SessionAuthenticationService $sessionAuthenticationService)
    {
        $this->sessionAuthenticationService = $sessionAuthenticationService;
    }
    public function getCartProducts(): void
    {
        session_start();
        if (!($this->sessionAuthenticationService->check())) {
            header('Location: /login');
        }
        $user = $this->sessionAuthenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }
        $userId = $user->getId();

        $userProducts = UserProduct::getCart($userId);

        if (!empty($userProducts)) {
            foreach ($userProducts as $userProduct) {
                $productIds[] = $userProduct->getProductId();
            }

            $products = Product::getAllByIds($productIds);
        }
        require_once './../View/cart.phtml';
    }

    public function removeProductFromCart(RemoveProductRequest $request): void
    {
        $userId = $request->getUserId();
        $productId = $request->getProductId();

        UserProduct::deleteProduct($productId, $userId);

        header('Location: /cart');
    }
}