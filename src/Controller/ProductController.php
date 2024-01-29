<?php

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
        $errorsQuantity = $this->validateQuantity($_POST);

        if (empty($errorsQuantity)) {
            $userId = $_SESSION['user_id'];
            $productId = $_POST['product-id'];
            $quantity = $_POST['quantity'];

            $this->userProduct->create($userId, $productId, $quantity);

            header('Location: /main');
        } else {

            $products = $this->product->getAll();
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

}

