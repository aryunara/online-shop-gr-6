<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\MinusProductRequest;
use Request\PlusProductRequest;
use Request\RemoveProductRequest;

class ProductController
{

    public function getCatalog(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        } else {
            $userId = $_SESSION['user_id'];
            $quantity = 0;

            $products = Product::getAll();
            $productsCount = $this->countProducts($userId);

            require_once './../View/catalog.phtml';
        }
    }

    public function getCartProducts(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        } else {
            $userId = $_SESSION['user_id'];

            $cart = UserProduct::getCart($userId);
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

    public function countProducts($userId): int
    {
        $cart = UserProduct::getCart($userId);
        if ($cart === null) {
            return 0;
        }
        return count($cart);
    }

    public function plus(PlusProductRequest $request)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        }
        $productId = $request->getId();
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

    public function minus(MinusProductRequest $request)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
        }
        $productId = $request->getId();
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

    public function removeProductFromCart(RemoveProductRequest $request): void
    {
        $userId = $request->getUserId();
        $productId = $request->getProductId();

        UserProduct::deleteProduct($productId, $userId);

        header('Location: /cart');
    }

}

