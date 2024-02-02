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

    public function getCartProducts(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        } else {
            $user_id = $_SESSION['user_id'];

            $cart = UserProduct::getCart($user_id);
            $total = 0;

            if (!empty($cart)) {
                foreach ($cart as $productInCart) {
                    $productId = $productInCart->getProductId();
                    $productInfo = Product::getOneById($productId);
                    $productsInfo[] = $productInfo;
                }
            }
            require_once './../View/cart.phtml';
        }
    }

    public function countProducts($user_id): int
    {
        $cart = UserProduct::getCart($user_id);
        if ($cart === null) {
            return 0;
        }
        return count($cart);
    }

    public function plus()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        }
        $productId = $_POST['product-id'];
        $userId = $_SESSION['user_id'];

        $product = UserProduct::getProductInCartInfo($productId, $userId);
        if (isset($product)) {

            $product->setQuantity($product->getQuantity() + 1);
            $quantity = $product->getQuantity();
            $product->save($quantity, $productId, $userId);
        } else {
            $quantity = 1;
            UserProduct::create($userId, $productId, $quantity);
        }
        header('Location: /main');
    }

    public function minus()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        }
        $productId = $_POST['product-id'];
        $userId = $_SESSION['user_id'];

        $product = UserProduct::getProductInCartInfo($productId, $userId);
        if (isset($product)) {
            $product->setQuantity($product->getQuantity() - 1);
            if ($product->getQuantity() < 1) {
                UserProduct::deleteProduct($productId, $userId);
            } else {
            $quantity = $product->getQuantity();
            $product->save($quantity, $productId, $userId);
            }
        }
        header('Location: /main');
    }

    public function getProductQuantity($productInfo)
    {
        $productId = $productInfo->getId();
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

