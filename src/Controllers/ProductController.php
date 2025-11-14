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

    public function add(RequestInterface $request, ResponseInterface $response)
    {
        $productId = $request->getParsedBody()['product_id'];

        if (!$this->cartService->productExist($productId)){
            $this->cartService->add($productId);
        }else{
            $cartId = $this->cartService->getCartId();

            $cartItem = ORM::forTable('cart_items')
                ->where('cart_id', $cartId)
                ->where('product_id', $productId)
                ->findOne();

            $cartItem->set('count', $cartItem['count'] + 1)->save();
        }


        return $response->withHeader('Location', '/products')->withStatus(302);
    }

    public function subtract(RequestInterface $request, ResponseInterface $response)
    {
        $productId = $request->getParsedBody()['product_id'];

        $cartId = $_COOKIE['cart_id'];

        $cartItem = ORM::forTable('cart_items')
            ->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->findOne();

        if ($cartItem['count'] == 1){
            ORM::forTable('cart_items')->findOne($cartItem['id'])->delete();
        }

        $cartItem->set('count', $cartItem['count'] - 1)->save();

        return $response->withHeader('Location', '/products')->withStatus(302);

    }
}