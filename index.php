<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\PhpRenderer;
use Src\Controllers\Auth\LoginController;
use Src\Controllers\Auth\OrderController;
use Src\Controllers\Auth\RegisterController;
use Src\Controllers\CartController;
use Src\Controllers\ProductController;
use Src\Middleware\AuthMiddleware;

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


$app->get('/login', [LoginController::class, 'loginPage']);
$app->post('/login',[LoginController::class, 'login']);
$app->get('/register', [RegisterController::class, 'registerPage']);
$app->post('/register',[RegisterController::class, 'register']);

$app->get('/products', [ProductController::class, 'index']);
$app->get('/products/{id}', [ProductController::class, 'show']);

$app->post('/products/add', [ProductController::class, 'add']);
$app->post('/products/subtract', [ProductController::class, 'subtract']);

$app->post('/cart/add', [CartController::class, 'add']);
$app->post('/cart/subtract', [CartController::class, 'subtract']);

$app->get('/cart', [CartController::class, 'index']);


$app->group('/', function () use ($app) {
    $app->get('/orders', [OrderController::class, 'index']);
    $app->get('/orders/add', [OrderController::class, 'store']);
    $app->get('/logout', [LoginController::class, 'logout']);
})->add(new AuthMiddleware($container->get(ResponseFactory::class)));

$app->run();