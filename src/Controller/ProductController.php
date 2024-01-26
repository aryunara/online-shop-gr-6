<?php

class ProductController
{
    public function getCatalog()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        } else {

            $pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");

            $stmt = $pdo->query('SELECT * FROM products');
            $products = $stmt->fetchAll();

            require_once './../View/catalog.phtml';
        }
    }

    public function addProduct()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /get_login.phtml');
        }

        $errorsQuantity = $this->validateQuantity($_POST);

        if (empty($errorsQuantity)) {
            try {
                $pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");
                $userId = $_SESSION['user_id'];
                $productId = $_POST['product-id'];
                $quantity = $_POST['quantity'];

                $stmt = $pdo->prepare("INSERT INTO user_products (user_id, product_id, quantity) VALUES (:userId, :productId, :quantity)");
                $stmt->execute(['userId' => $userId, 'productId' => $productId, 'quantity' => $quantity]);

                header('Location: /main');
            } catch(PDOException) {
                $errorsQuantity['quantity'] = 'Укажите целое число';
            }
        }
        $pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");

        $stmt = $pdo->query('SELECT * FROM products');
        $products = $stmt->fetchAll();

        require_once './../View/catalog.phtml';
    }

    private function validateQuantity() : array
    {
        $errorsQuantity = [];

        if (isset($_POST['quantity'])) {
            $quantity = $_POST['quantity'];

            if (empty($quantity)) {
                $errorsQuantity['quantity'] = 'Укажите количество';
            }
            if ($quantity < 1) {
                $errorsQuantity['quantity'] = 'Количество должно быть больше 0';
            }
        } else {
            $errorsQuantity['quantity'] = 'Поле quantity не указано';
        }
        return $errorsQuantity;
    }
}
