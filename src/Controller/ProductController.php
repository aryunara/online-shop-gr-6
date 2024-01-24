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

            require_once './../View/catalog.php';
        }
    }

    public function addProduct()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /get_login.php');
        }


    }
}