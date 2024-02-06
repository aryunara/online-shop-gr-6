<?php


use Controller\ProductController;
use Controller\UserController;

require_once './../App/Autoloader.php';
require_once './../App/App.php';

Autoloader::registrate();

$app = new App();

$app->get('/registrate', UserController::class,'getRegistrate');
$app->get('/login', UserController::class, 'getLogin');
$app->get('/main',ProductController::class,'getCatalog');
$app->get('/cart', ProductController::class, 'getCartProducts');
$app->get('/countProducts', ProductController::class, 'countProducts');

$app->post('/registrate',UserController::class,'postRegistrate', \Request\RegistrateRequest::class);
$app->post('/login', UserController::class, 'postLogin', \Request\LoginRequest::class);
$app->post('/logout', UserController::class, 'logout');
$app->post('/product-plus', ProductController::class, 'plus', \Request\PlusProductRequest::class);
$app->post('/product-minus', ProductController::class, 'minus', \Request\MinusProductRequest::class);
$app->post('/remove-product', ProductController::class, 'removeProductFromCart', \Request\RemoveProductRequest::class);


$app->run();

