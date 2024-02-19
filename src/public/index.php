<?php


use Controller\CartController;
use Controller\OrderController;
use Controller\ProductController;
use Controller\UserController;

use Request\LoginRequest;
use Request\MinusProductRequest;
use Request\OrderRequest;
use Request\PlusProductRequest;
use Request\RegistrateRequest;
use Request\RemoveProductRequest;

require_once './../Core/Autoloader.php';
require_once './../Core/App.php';

Autoloader::registrate();

$app = new App();

$app->get('/registrate', UserController::class,'getRegistrate');
$app->get('/login', UserController::class, 'getLogin');
$app->get('/main',ProductController::class,'getCatalog');
$app->get('/cart', CartController::class, 'getCartProducts');
$app->get('/order', OrderController::class, 'getOrderPage');

$app->post('/registrate',UserController::class,'postRegistrate', RegistrateRequest::class);
$app->post('/login', UserController::class, 'postLogin', LoginRequest::class);
$app->post('/logout', UserController::class, 'logout');
$app->post('/product-plus', CartController::class, 'plus', PlusProductRequest::class);
$app->post('/product-minus', CartController::class, 'minus', MinusProductRequest::class);
$app->post('/remove-product', CartController::class, 'removeProductFromCart', RemoveProductRequest::class);
$app->post('/order', OrderController::class, 'postOrderPage', OrderRequest::class);


$app->run();

