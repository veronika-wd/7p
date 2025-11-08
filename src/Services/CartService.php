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

    public function productExist(int $productId)
    {
        return ORM::forTable('cart_items')->where('product_id', $productId)->findOne();
    }

    public function getCartId()
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