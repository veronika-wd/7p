<?php

namespace Src\Services;

use ORM;

class CartService
{
    private const COOKIE_NAME = 'cart_id';

    public  function  add(int $productId): void
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

    public function productExist(int $productId): ORM|bool
    {
        return ORM::forTable('cart_items')
            ->where('cart_id', $this->getCartId())
            ->where('product_id', $productId)
            ->findOne();
    }

    public function getCartId(): int
    {
        if(isset($_COOKIE[self::COOKIE_NAME])){
            return $_COOKIE[self::COOKIE_NAME];
        }

        $cart = ORM::forTable('carts')->create();
        $cart->save();

        setcookie(self::COOKIE_NAME, $cart->id, time() + 60 * 60 * 24 * 31, '/');

        return $cart->id;
    }
}