<?php


use Controller\Cart;
use Controller\OrderController;
use Controller\ProductController;
use Controller\UserController;
use Request\RemoveProductRequest;

require_once './../App/Autoloader.php';
require_once './../App/App.php';

Autoloader::registrate();

$app = new App();

$app->get('/registrate', UserController::class,'getRegistrate');
$app->get('/login', UserController::class, 'getLogin');
$app->get('/main',ProductController::class,'getCatalog');
$app->get('/cart', Cart::class, 'getCartProducts');
$app->get('/countProducts', ProductController::class, 'countProducts');
$app->get('/order', OrderController::class, 'getOrderPage');

$app->post('/registrate',UserController::class,'postRegistrate', \Request\RegistrateRequest::class);
$app->post('/login', UserController::class, 'postLogin', \Request\LoginRequest::class);
$app->post('/logout', UserController::class, 'logout');
$app->post('/product-plus', ProductController::class, 'plus', \Request\PlusProductRequest::class);
$app->post('/product-minus', ProductController::class, 'minus', \Request\MinusProductRequest::class);
$app->post('/remove-product', Cart::class, 'removeProductFromCart', RemoveProductRequest::class);
$app->post('/order', OrderController::class, 'postOrderPage', \Request\OrderRequest::class);


$app->run();

