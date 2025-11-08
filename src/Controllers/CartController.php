<?php

namespace Src\Controllers;

use ORM;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Views\PhpRenderer;
use Src\Services\CartService;

class CartController extends Controller
{
    public function __construct(
        PhpRenderer $renderer,
        protected CartService $cartService
    )
    {
        parent::__construct($renderer);
    }

    public function add( RequestInterface $request, ResponseInterface $response)
    {
        $productId = $request->getParsedBody()['product_id'];

        if (!$this->cartService->productExist($productId)){
            $this->cartService->add($productId);
        }else{
            $cartId = $_COOKIE['cart_id'];

            $cartItem = ORM::forTable('cart_items')
                ->where('cart_id', $cartId)
                ->where('product_id', $productId)
                ->findOne();

            $cartItem->set('count', $cartItem['count'] + 1)->save();
        }


        return $response->withHeader('Location', '/products')->withStatus(302);
    }
}