<?php


use Controller\CartController;
use Controller\OrderController;
use Controller\ProductController;
use Controller\UserController;

use Core\Container;
use Request\LoginRequest;
use Request\MinusProductRequest;
use Request\OrderRequest;
use Request\PlusProductRequest;
use Request\RegistrateRequest;
use Request\RemoveProductRequest;
use Service\Authentication\AuthenticationServiceInterface;
use Service\Authentication\SessionAuthenticationService;
use Service\OrderService;

require_once './../Core/Autoloader.php';
require_once './../Core/App.php';

Autoloader::registrate();

$container = new Container();

$container->set(AuthenticationServiceInterface::class, function () {
    return new SessionAuthenticationService();
});

$container->set(OrderService::class, function () {
    return new OrderService();
});

$container->set(UserController::class, function (Container $container) {
    $authenticationService = $container->get(AuthenticationServiceInterface::class);

    return new UserController($authenticationService);
});

$container->set(ProductController::class, function (Container $container) {
    $authenticationService = $container->get(AuthenticationServiceInterface::class);

    return new ProductController($authenticationService);
});

$container->set(CartController::class, function (Container $container) {
    $authenticationService = $container->get(AuthenticationServiceInterface::class);

    return new CartController($authenticationService);
});

$container->set(OrderController::class, function (Container $container) {
    $authenticationService = $container->get(AuthenticationServiceInterface::class);
    $orderService = $container->get(OrderService::class);

    return new OrderController($authenticationService, $orderService);
});

$container->set(PDO::class, function () {
    $host = getenv('DB_HOST', 'db');
    $db = getenv('DB_DATABASE', 'db');
    $user = getenv('DB_USER', 'aryuna');
    $password = getenv('DB_PASSWORD', '030201');

    return new PDO("pgsql:host=$host; port=5432; dbname=$db", $user, $password);
});

$app = new App($container);

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

