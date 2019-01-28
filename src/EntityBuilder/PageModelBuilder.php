<?php

namespace App\EntityBuilder;

use App\Entity\Cart;
use App\Service\CartService;

class PageModelBuilder
{
    /**
     * @param Cart|null $cart
     * @return array
     */
    public static function toDefaultArray(Cart $cart = null)
    {
        return [
            'cart' => [
                'totalItems' => is_null($cart) ? 0 : count($cart->getCartItems()),
                'total' => is_null($cart) ? 0 : $cart->calculateTotal(),
                'items' => is_null($cart) ? 0 : $cart->getCartItems()
            ]
        ];
    }
}