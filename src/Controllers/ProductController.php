<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ProductController extends Controller
{
    public function index(RequestInterface $request, ResponseInterface $response, array $args)
    {
        $cartId = $_COOKIE['cart_id'] ?? '';

        $cartItems = ORM::forTable('cart_items')->where('cart_id', $cartId)->findArray();

        return $this->renderer->render($response, 'index.php', [
            'products' => ORM::forTable('products')->findArray(),
            'cartItems' => $cartItems,
        ]);
    }

    public function show(RequestInterface $request, ResponseInterface $response, array $args)
    {
        $productId = $args['id'];

        return $this->renderer->render($response, 'show.php', [
            'product' => ORM::forTable('products')->findOne($productId),
        ]);
    }
}