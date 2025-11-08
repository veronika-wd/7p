<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Src\Controllers\CartController;
use Src\Controllers\ProductController;

require __DIR__ . '/vendor/autoload.php';

session_start();

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

$container->set(PhpRenderer::class, function () {
    return new PhpRenderer(__DIR__ . '/templates');
});

ORM::configure('mysql:host=database;dbname=docker');
ORM::configure('username', 'docker');
ORM::configure('password', 'docker');

$app->get('/products', [ProductController::class, 'index']);
$app->get('/products/{id}', [ProductController::class, 'show']);
$app->post('/products/{id}/order', [ProductController::class, 'store']);

$app->post('/cart/add', [CartController::class, 'add']);
$app->post('/cart/subtract', [CartController::class, 'subtract']);

$app->run();