<?php

namespace Src\Services;

use ORM;

class CartService
{
    public  function  add(int $productId)
    {
        $cardId = $this->getCartId();

        ORM::forTable('cart_items')
            ->create([
                'cart_id' => $cardId,
                'product_id' => $productId,
                'count' => 1
            ])
        ->save();
    }

    protected function getCartId()
    {
        if(isset($_COOKIE['cart_id'])){
            return $_COOKIE['cart_id'];
        }

        $cart = ORM::forTable('carts')->create();
        $cart->save();

        setcookie('cart_id', $cart->id, time() + 60 * 60 * 24 * 31);

        return $cart->id;
    }
}