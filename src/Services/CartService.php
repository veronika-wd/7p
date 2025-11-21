<?php

namespace Src\Services;

use ORM;

class CartService
{
    private const COOKIE_NAME = 'cart_id';

    public function add(int $productId): void
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

    public function getCartItems(?int $cartId = null): array
    {
        $currentCartId = $cartId ?? $this->getCartId();

        return ORM::forTable('cart_items')
            ->where('cart_id', $currentCartId)
            ->findArray();
    }

    public function getGroupedCartItems(?int $cartId = null): array
    {
        $cartItems = $this->getCartItems($cartId);
        $result = [];

        foreach ($cartItems as $cartItem) {
            $result[$cartItem['product_id']] = $cartItem['count'];
        }

        return $result;

    }

    public function getCartId(): int
    {
        $userId = $_SESSION['user_id'] ?? null;

        $currentCart = ORM::forTable('carts')->where([
            'user_id' => $userId,
            'status' => 'active',
        ])->findOne();

        if ($currentCart) {
            return $currentCart->id;
        }

        if (isset($_COOKIE[self::COOKIE_NAME])) {
            return $_COOKIE[self::COOKIE_NAME];
        }

        $cart = ORM::forTable('carts')->create([
            'user_id' => $userId,
        ]);
        $cart->save();

        setcookie(self::COOKIE_NAME, $cart->id, time() + 60 * 60 * 24 * 31, '/');

        return $cart->id;
    }
}