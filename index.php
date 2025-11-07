<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Views\PhpRenderer;
use Src\Controllers\AdminControllers\ApplicationController;
use Src\Controllers\AdminControllers\CategoryController;
use Src\Controllers\AdminControllers\GoodController;
use Src\Controllers\HomeController;
use Src\Controllers\Auth\LoginController;
use Src\Controllers\Auth\RegisterController;
use Src\Controllers\UserController;
use Src\Middleware\AdminMiddleware;
use Src\Middleware\AuthMiddleware;

require __DIR__ . '/vendor/autoload.php';

session_start();

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

$container->set(PhpRenderer::class, function () {
    return new PhpRenderer(__DIR__ . '/templates', [
        'categories' => ORM::for_table('categories')
            ->whereNull('parent_category')
            ->findArray(),
    ]);
});

ORM::configure('mysql:host=database;dbname=docker');
ORM::configure('username', 'docker');
ORM::configure('password', 'docker');

$app->get('/login', [LoginController::class, 'loginPage']);
$app->post('/login',[LoginController::class, 'login']);
$app->get('/register', [RegisterController::class, 'registerPage']);
$app->post('/register',[RegisterController::class, 'register']);
$app->get('/', [HomeController::class, 'index']);

$app->get('/catalog', [HomeController::class, 'catalog']);
$app->get('/show/{slug}', [HomeController::class, 'show']);

$app->get('/applications/{goodSlug}/create', [ApplicationController::class, 'create']);
$app->post('/applications/{goodSlug}/create', [ApplicationController::class, 'store']);


$app->group('/', function () use ($app) {
    $app->get('/profile', [UserController::class, 'index']);

    // Выход
    $app->get('/logout', [LoginController::class, 'logout']);
})->add(new AuthMiddleware($container->get(ResponseFactory::class)));


$app->group('/', function () use ($app) {
// CRUD категорий
    $app->get('/categories', [CategoryController::class, 'index']);

    $app->get('/categories/create', [CategoryController::class, 'create']);
    $app->post('/categories/create', [CategoryController::class, 'store']);

    $app->get('/categories/{id}/edit', [CategoryController::class, 'edit']);
    $app->post('/categories/{id}/edit', [CategoryController::class, 'update']);

    $app->get('/categories/{id}/delete', [CategoryController::class, 'delete']);

// CRUD товаров
    $app->get('/goods', [GoodController::class, 'index']);

    $app->get('/goods/create', [GoodController::class, 'create']);
    $app->post('/goods/create', [GoodController::class, 'store']);

    $app->get('/goods/{id}/edit', [GoodController::class, 'edit']);
    $app->post('/goods/{id}/edit', [GoodController::class, 'update']);

    $app->get('/goods/{id}/delete', [GoodController::class, 'delete']);

//    CRUD заявок
    $app->get('/applications', [ApplicationController::class, 'index']);

    $app->get('/applications/{id}/edit', [ApplicationController::class, 'edit']);
    $app->post('/applications/{id}/edit', [ApplicationController::class, 'update']);

    $app->get('/applications/{id}/delete', [ApplicationController::class, 'delete']);
})->add(new AdminMiddleware($container->get(ResponseFactory::class)));

$app->get('/{slug}',  [HomeController::class, 'index']);

$app->run();