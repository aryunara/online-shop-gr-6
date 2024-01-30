<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;

class ProductController
{
    private Product $product;
    private UserProduct $userProduct;

    public function __construct()
    {
        $this->product = new Product();
        $this->userProduct = new UserProduct();
    }

    public function getCatalog(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        } else {
            $user_id = $_SESSION['user_id'];
            $quantity = 0;

            $products = $this->product->getAll();
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
        $quantity = $this->minusProduct($_POST) + $this->plusProduct($_POST);
        $errorsQuantity = $this->validateQuantity($quantity);

        $userId = $_SESSION['user_id'];
        $productId = $_POST['product-id'];
        $cart = $this->userProduct->getCart($userId);

        if (empty($errorsQuantity)) {

            foreach ($cart as $productInCart) {
                if ($productInCart['product_id'] = $productId) {
                    $this->userProduct->deleteProduct($productId, $userId);
                    $productInCart['quantity'] = $quantity;
                }
                $quantity = $productInCart['quantity'];
            }

            $this->userProduct->create($userId, $productId, $quantity);
        }

            $products = $this->product->getAll();
            $productsCount = $this->countProducts($_SESSION['user_id']);

            require_once './../View/catalog.phtml';
        }

    private function validateQuantity($quantity): array
    {
        $errorsQuantity = [];

        if (isset($quantity)) {

            if ($quantity < 1) {
                $errorsQuantity['quantity'] = 'Количество должно быть больше 0';
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

            $cart = $this->userProduct->getCart($user_id);
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
        $cart = $this->userProduct->getCart($user_id);
        return count($cart);
    }

    public function plusProduct()
    {
        if (isset($_POST['plus'])) {
            $quantity = $_POST['plus'];
            $quantity = (int)$quantity;
            $quantity++;
            return $quantity;
        } else {
            return 0;
        }
    }

    public function minusProduct()
    {
        if (isset($_POST['minus'])) {
            $quantity = $_POST['minus'];
            $quantity = (int)$quantity;
            $quantity--;
            return $quantity;
        } else {
            return 0;
        }
    }

    public function getProductQuantity($product)
    {
        $productId = $product['id'];
        $userId = $_SESSION['user_id'];
        $quantity = $this->userProduct->getQuantity($productId, $userId);
        if (empty($quantity)) {
            return 0;
        } else {
            return $quantity['quantity'];
        }
    }

}

