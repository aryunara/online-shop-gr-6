<?php

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri === '/login') {
    require_once './../Controller/UserController.php';
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
    require_once './../Controller/UserController.php';
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
    require_once './../Controller/ProductController.php';
    $obj = new ProductController();
    $obj->getCatalog();
    }

elseif ($requestUri === '/add-product') {
    require_once './../Controller/ProductController.php';
    $obj = new ProductController();
    $obj->addProduct();
    }

else {
    require_once './../View/404.html';
}