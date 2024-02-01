<?php

use Controller\ProductController;
use Controller\UserController;

class App
{

    private array $routes = [
        '/registrate' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getRegistrate',
                ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'postRegistrate',
                ],
            ],
        '/login' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getLogin',
                ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'postLogin',
                ],
            ],
        '/main' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'getCatalog',
                ],
            ],
        '/add-product' => [
            'POST' => [
                'class' => ProductController::class,
                'method' => 'addProduct',
                ],
            ],
        '/cart' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'getCartProducts',
                ],
            ],
        '/plus' => [
            'POST' => [
                'class' => ProductController::class,
                'method' => 'getCatalog',
                ],
            ],
        '/minus' => [
            'POST' => [
                'class' => ProductController::class,
                'method' => 'getCatalog',
                ],
            ],
        ];

    public function run(): void
    {

        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($requestUri === '/login') {
            if ($requestMethod === 'GET') {
                $obj = new UserController();
                $obj->getLogin();
            } elseif ($requestMethod === 'POST') {
                $obj = new UserController();
                $obj->postLogin();
            } else {
                echo "Метод $requestMethod не поддерживается для адреса $requestUri";
            }

        } elseif ($requestUri === '/registrate') {
            if ($requestMethod === 'GET') {
                $obj = new UserController();
                $obj->getRegistrate();
            } elseif ($requestMethod === 'POST') {
                $obj = new UserController();
                $obj->postRegistrate();
            } else {
                echo "Метод $requestMethod не поддерживается для адреса $requestUri";
            }

        } elseif ($requestUri === '/main') {
            $obj = new ProductController();
            $obj->getCatalog();
        }

        elseif ($requestUri === '/add-product') {
            $obj = new ProductController();
            $obj->addProduct();
        }

        elseif ($requestUri === '/cart') {
            $obj = new ProductController();
            $obj->getCartProducts();
        }

        elseif ($requestUri === '/plus') {
            $obj = new ProductController();
            $obj->getCatalog();
        }

        elseif ($requestUri === '/minus') {
            $obj = new ProductController();
            $obj->getCatalog();
        }

        elseif ($requestUri === '/logout') {
            $obj = new UserController();
            $obj->logout();
        }

        elseif ($requestUri === '/remove-product') {
            $obj = new ProductController();
            $obj->removeProductFromCart();
        }

        else {
            require_once './../View/404.html';
        }
    }
}