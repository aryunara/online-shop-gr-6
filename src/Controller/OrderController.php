<?php

namespace Controller;

use Model\Order;
use Model\OrderedProduct;
use Model\Product;
use Model\UserProduct;
use Request\OrderRequest;
use Service\SessionAuthenticationService;

class OrderController
{
    private SessionAuthenticationService $sessionAuthenticationService;

    public function __construct(SessionAuthenticationService $sessionAuthenticationService)
    {
        $this->sessionAuthenticationService = $sessionAuthenticationService;
    }

    public function getOrderPage(): void
    {
        if (!($this->sessionAuthenticationService->check())) {
            header('Location: /login');
        }

        $user = $this->sessionAuthenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $userId = $user->getId();
        $userProducts = UserProduct::getCart($userId);
        $products = $this->getOrderedProducts($userId);

        require_once './../View/order.phtml';
    }

    public function postOrderPage(OrderRequest $request): void
    {
        if (!($this->sessionAuthenticationService->check())) {
            header('Location: /login');
        }

        $user = $this->sessionAuthenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $userId = $user->getId();
        $errors = $request->validate();
        $userProducts = UserProduct::getCart($userId);

        if (empty($errors)) {
            if (!empty($userProducts)) {
                //Сохраняем данные клиента в таблицу заказов
                $name = $request->getName();
                $phone = $request->getPhone();
                $email = $request->getEmail();
                $address = $request->getAddress();
                $comment = $request->getComment();

                Order::create($userId, $name, $phone, $email, $address, $comment);

                //Сохраняем продукты в таблицу заказанных продуктов
                $orderId = Order::getLastByUserId($userId)->getOrderId();

                $products = $this->getOrderedProducts($userId);

                foreach ($userProducts as $userProduct) {
                    $product = $products[$userProduct->getProductId()];
                    $productId = $product->getId();
                    $quantity = $userProduct->getQuantity();
                    $total = $product->getPrice() * $userProduct->getQuantity();

                    OrderedProduct::create($orderId, $productId, $quantity, $total);
                }
                header('Location: /main');
            } else {
                header('Location: /cart');
            }
        } else {
            $products = $this->getOrderedProducts($userId);

            require_once './../View/order.phtml';
        }
    }

    private function getOrderedProducts($userId): ?array
    {
        $userProducts = UserProduct::getCart($userId);
        if (!empty($userProducts)) {
            foreach ($userProducts as $userProduct) {
                $productIds[] = $userProduct->getProductId();
            }
            $products = Product::getAllByIds($productIds);
        }

        return $products ?? null;
    }
}