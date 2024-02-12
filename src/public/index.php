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

require_once './../App/Autoloader.php';
require_once './../App/App.php';

Autoloader::registrate();

$app = new App();

$app->get('/registrate', UserController::class,'getRegistrate');
$app->get('/login', UserController::class, 'getLogin');
$app->get('/main',ProductController::class,'getCatalog');
$app->get('/cart', CartController::class, 'getCartProducts');
$app->get('/countProducts', ProductController::class, 'countProducts');
$app->get('/order', OrderController::class, 'getOrderPage');

$app->post('/registrate',UserController::class,'postRegistrate', RegistrateRequest::class);
$app->post('/login', UserController::class, 'postLogin', LoginRequest::class);
$app->post('/logout', UserController::class, 'logout');
$app->post('/product-plus', ProductController::class, 'plus', PlusProductRequest::class);
$app->post('/product-minus', ProductController::class, 'minus', MinusProductRequest::class);
$app->post('/remove-product', CartController::class, 'removeProductFromCart', RemoveProductRequest::class);
$app->post('/order', OrderController::class, 'postOrderPage', OrderRequest::class);


$app->run();

