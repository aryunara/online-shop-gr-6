<?php

class ProductController
{
    public function getCatalog(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        } else {
            $user_id = $_SESSION['user_id'];
            $productModel = new Product();
            $products = $productModel->getAll();
            $productsCount = $this->countProducts($user_id);

            require_once './../View/catalog.phtml';
        }
    }

    public function addProduct(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        }
        $errorsQuantity = $this->validateQuantity($_POST);

        if (empty($errorsQuantity)) {
            $userId = $_SESSION['user_id'];
            $productId = $_POST['product-id'];
            $quantity = $_POST['quantity'];

            $userProductModel = new UserProduct();
            $userProductModel->create($userId, $productId, $quantity);

            header('Location: /main');
        } else {

            $productModel = new Product();
            $products = $productModel->getAll();
            $productsCount = $this->countProducts($_SESSION['user_id']);

            require_once './../View/catalog.phtml';
        }
    }

    private function validateQuantity(): array
    {
        $errorsQuantity = [];

        if (isset($_POST['quantity'])) {
            $quantity = $_POST['quantity'];
            $quantity = (float)$quantity;

            if (empty($quantity)) {
                $errorsQuantity['quantity'] = 'Укажите количество';
            }
            if ($quantity < 1) {
                $errorsQuantity['quantity'] = 'Количество должно быть больше 0';
            }
            if (floor($quantity) !== $quantity) {
                $errorsQuantity['quantity'] = 'Укажите целое число';
            }
        } else {
            $errorsQuantity['quantity'] = 'Поле quantity не указано';
        }
        return $errorsQuantity;
    }

    public function getCartProducts()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        } else {
            $user_id = $_SESSION['user_id'];

            $userProductModel = new UserProduct();
            $cart = $userProductModel->getCart($user_id);
            $i = 0;
            $total = 0;
            $productCount = count($cart);

            foreach ($cart as $productInCart) {
                $userProductModel = new UserProduct();
                $productsInCartInfo[] = $userProductModel->getProductInCartInfo($productInCart['product_id']);
            }
        }
        require_once './../View/cart.phtml';
    }

    public function countProducts($user_id): int
    {
        $userProductModel = new UserProduct();
        $cart = $userProductModel->getCart($user_id);
        return count($cart);
    }

}

