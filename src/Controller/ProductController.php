<?php

class ProductController
{
    public function getCatalog()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        } else {
            echo "Hello, {$_SESSION['user_name']}!";

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

        $pdo = new PDO("pgsql:host=db; port=5432; dbname=db", "aryuna", "030201");
        $userId = $_SESSION['user_id'];
        $productId = $_POST['product-id'];
        $quantity = $_POST['quantity'];

        $stmt = $pdo->prepare("INSERT INTO user_products (user_id, product_id, quantity) VALUES (:userId, :productId, :quantity)");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'quantity' => $quantity]);

        echo "Продукты добавлены!";
    }
}
