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

$app->post('/registrate',UserController::class,'postRegistrate');
$app->post('/login', UserController::class, 'postLogin');
$app->post('/logout', UserController::class, 'logout');
$app->post('/add-product', ProductController::class, 'addProduct');
$app->post('/plus', ProductController::class, 'getCatalog');
$app->post('/minus', ProductController::class, 'getCatalog');
$app->post('/remove-product', ProductController::class, 'removeProductFromCart');


$app->run();

