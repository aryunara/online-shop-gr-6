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
        '/logout' => [
            'POST' => [
                'class' => UserController::class,
                'method' => 'logout',
            ],
        ],
        '/remove-product' => [
            'POST' => [
                'class' => ProductController::class,
                'method' => 'removeProductFromCart',
            ],
        ],
    ];

    public function run(): void
    {

        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestUri][$requestMethod])) {
            $handler = $this->routes[$requestUri][$requestMethod];
            $class = $handler['class'];
            $method = $handler['method'];

            if (isset ($class)) {
                if (isset($method)) {
                    $obj = new $class;
                    $obj->$method();
                } else {
                    echo "Метод $method не поддерживается для адреса $requestUri";
                }
            } else {
                echo "Класс $class не найден";
            }
        } else {
            require_once './../View/404.html';
        }
    }
}