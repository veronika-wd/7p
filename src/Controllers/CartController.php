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


    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $cartItems = $this->cartService->getGroupedCartItems();
//        $closedCarts = ORM::forTable('carts')->where([
//            'user_id' => $_SESSION['user_id'],
//            'status' => 'closed',
//        ])->findArray();

        return $this->renderer->render($response, 'cart/index.php', [
            'products' => ORM::forTable('products')->findArray(),
            'cartItems' => $cartItems,
//            'closedCarts' => $closedCarts,
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


        return $response->withHeader('Location', '/cart')->withStatus(302);
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

        return $response->withHeader('Location', '/cart')->withStatus(302);

    }

    public function order(RequestInterface $request, ResponseInterface $response)
    {
        $userId = $_SESSION['user_id'];

        $cartId = $this->cartService->getCartId();
        $cartItems = $this->cartService->getCartItems();

        foreach ($cartItems as $cartItem) {
            $product = ORM::forTable('products')->findOne($cartItem['product_id']);

            ORM::forTable('cart_items')->findOne($cartItem['id'])->set([
                'price' => $product['price'],
            ])->save();
        }

        ORM::forTable('orders')->create([
            'user_id' => $userId,
            'cart_id' => $cartId,
        ])->save();

        ORM::forTable('carts')->findOne($cartId)->set([
            'status' => 'closed',
        ])->save();


        setcookie('cart_id', $cartId, time() - 60 * 60 * 24 * 31, '/');

        return $response->withHeader('Location', '/cart')->withStatus(302);
    }
}