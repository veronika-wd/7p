<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Services\CartService;

class ProductController extends Controller
{
    public function __construct(PhpRenderer $renderer, protected CartService $cartService)
    {
        parent::__construct($renderer);
    }

    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $cartItems = $this->cartService->getGroupedCartItems();

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