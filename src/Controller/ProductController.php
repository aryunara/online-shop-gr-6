<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;

class ProductController
{

    public function getCatalog(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        } else {
            $user_id = $_SESSION['user_id'];
            $quantity = 0;

            $products = Product::getAll();
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
        $cart = UserProduct::getCart($userId);

        if (empty($errorsQuantity)) {

            foreach ($cart as $productInCart) {
                if ($productInCart['product_id'] = $productId) {
                    UserProduct::deleteProduct($productId, $userId);
                    $productInCart['quantity'] = $quantity;
                }
                $quantity = $productInCart['quantity'];
            }

            UserProduct::create($userId, $productId, $quantity);
        }

            $products = Product::getAll();
            $productsCount = $this->countProducts($_SESSION['user_id']);

            header('Location: /main');
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

    public function getCartProducts(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        } else {
            $user_id = $_SESSION['user_id'];

            $cart = UserProduct::getCart($user_id);
            $i = 0;
            $total = 0;
            $productCount = count($cart);

            foreach ($cart as $productInCart) {
                $productInCartId = $productInCart['product_id'];
                $productFromCartInfo = Product::getProductFromCartInfo($productInCartId);
                $productsFromCartInfo[] = $productFromCartInfo;
            }
            require_once './../View/cart.phtml';
        }

    }

    public function countProducts($user_id): int
    {
        $cart = UserProduct::getCart($user_id);
        return count($cart);
    }

    public function plusProduct(): int
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

    public function minusProduct(): int
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
        $productId = $product->getId();
        $userId = $_SESSION['user_id'];
        $productInCartInfo = userProduct::getProductInCartInfo($productId, $userId);
        if (empty($productInCartInfo)) {
            return 0;
        } else {
            return $productInCartInfo->getQuantity();
        }
    }

    public function removeProductFromCart(): void
    {
        $userId = $_POST['user-id'];
        $productId = $_POST['product-id'];
        UserProduct::deleteProduct($productId, $userId);
        header('Location: /cart');
    }

}

