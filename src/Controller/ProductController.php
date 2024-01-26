<?php

class ProductController
{
    public function getCatalog(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        } else {
            require './../Model/Product.php';
            $productModel = new Product();
            $products = $productModel->getAll();

            require_once './../View/catalog.phtml';
        }
    }

    public function addProduct() : void
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

            require './../Model/UserProduct.php';
            $userProductModel = new UserProduct();
            $userProductModel->create($userId, $productId, $quantity);

            header('Location: /main');
        } else {
            require './../Model/Product.php';
            $productModel = new Product();
            $products = $productModel->getAll();

            require_once './../View/catalog.phtml';
        }
    }

    private function validateQuantity() : array
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
}
