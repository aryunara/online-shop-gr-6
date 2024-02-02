<?php

use Controller\ProductController;
use Controller\UserController;

class App
{

    private array $routes = [];

    public function get(string $url, string $class, string $handler): void
    {
        $this->routes[$url]['GET'] = [
            'class' => $class,
            'method' => $handler,
        ];
    }

    public function post(string $url, string $class, string $handler): void
    {
        $this->routes[$url]['POST'] = [
            'class' => $class,
            'method' => $handler,
        ];
    }

    public function run(): void
    {

        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestUri])) {
            $routeMethods = $this->routes[$requestUri];
            if (isset($routeMethods[$requestMethod])) {
                $handler = $routeMethods[$requestMethod];

                $class = $handler['class'];
                $method = $handler['method'];

                $obj = new $class();
                $obj->$method();
            } else {
                echo "Метод $requestMethod не поддерживается для адреса $requestUri";
            }
        } else {
            require_once './../View/404.html';
        }
    }
}