<?php

use Controller\ProductController;
use Controller\UserController;

class App
{

    private array $routes = [];

    public function get(string $url, string $class, string $handler): void {
        $this->routes[$url]['GET'] = [
            'class' => $class,
            'method' => $handler,
        ];
    }

    public function post(string $url, string $class, string $handler) : void
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