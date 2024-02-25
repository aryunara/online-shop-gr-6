<?php

namespace Controller;

use Core\ViewRenderer;
use Model\Product;
use Model\UserProduct;
use Request\OrderRequest;
use Service\Authentication\AuthenticationServiceInterface;
use Service\OrderService;

class OrderController extends MainController
{
    private OrderService $orderService;

    public function __construct(AuthenticationServiceInterface $authenticationService, ViewRenderer $viewRenderer, OrderService $orderService)
    {
        parent::__construct($authenticationService, $viewRenderer);
        $this->orderService = $orderService;
    }

    public function getOrderPage(): string
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

        return $this->viewRenderer->render('order.phtml', ['userProducts' => $userProducts, 'products' => $products], false);
    }

    public function postOrderPage(OrderRequest $request) : string
    {
        if (!$this->authenticationService->check()) {
            header('Location: /login');
        }

        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header('Location: /login');
        }

        $userId = $user->getId();
        $errors = $request->validate();
        $userProducts = UserProduct::getCart($userId);

        if (empty($errors)) {
            if (!empty($userProducts)) {
                $result = $this->orderService->create($userId, $request->getName(), $request->getPhone(), $request->getEmail(), $request->getAddress(), $request->getComment());
                if ($result) {
                    header('Location: /main');
                }

                return $this->viewRenderer->render('500.phtml', [], false);
            } else {
                header('Location: /cart');
            }
        } else {
            $products = Product::getProducts($userId);

            return $this->viewRenderer->render('order.phtml', ['userProducts' => $userProducts, 'products' => $products], false);
        }
    }
}