<?php

use Controller\CartController;
use Controller\OrderController;
use Controller\ProductController;
use Controller\UserController;
use Core\Container;
use Core\ViewRenderer;
use Service\Authentication\AuthenticationServiceInterface;
use Service\Authentication\SessionAuthenticationService;
use Service\OrderService;

return [
    AuthenticationServiceInterface::class => function () {
        return new SessionAuthenticationService();
    },
    OrderService::class => function (Container $container) {
        $pdo = $container->get(PDO::class);
        return new OrderService($pdo);
    },
    ViewRenderer::class => function () {
        return new ViewRenderer();
    },
    UserController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $viewRenderer = $container->get(ViewRenderer::class);

        return new UserController($authenticationService, $viewRenderer);
    },
    ProductController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $viewRenderer = $container->get(ViewRenderer::class);

        return new ProductController($authenticationService, $viewRenderer);
    },
    CartController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $viewRenderer = $container->get(ViewRenderer::class);

        return new CartController($authenticationService, $viewRenderer);
    },
    OrderController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $orderService = $container->get(OrderService::class);
        $viewRenderer = $container->get(ViewRenderer::class);

        return new OrderController($authenticationService, $viewRenderer, $orderService);
    },
    PDO::class => function () {
        $host = getenv('DB_HOST', 'db');
        $db = getenv('DB_DATABASE', 'db');
        $user = getenv('DB_USER', 'aryuna');
        $password = getenv('DB_PASSWORD', '030201');

        return new PDO("pgsql:host=$host; port=5432; dbname=$db", $user, $password);
    }
];