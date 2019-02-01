<?php

namespace App\EntityBuilder;

use App\Entity\Cart;
use App\Service\CartService;

class CartEntityBuilder extends AbstractEntityBuilder
{
    /**
     * @var CartService
     */
    private $cartService;
    /**
     * @var CartItemEntityBuilder
     */
    private $cartItemEntityBuilder;

    /**
     * CartEntityBuilder constructor.
     * @param CartService $cartService
     * @param CartItemEntityBuilder $cartItemEntityBuilder
     */
    public function __construct(CartService $cartService, CartItemEntityBuilder $cartItemEntityBuilder)
    {
        $this->cartService = $cartService;
        $this->cartItemEntityBuilder = $cartItemEntityBuilder;
    }


    /**
     * @param Cart|null $cart
     * @return array
     * @throws \Exception
     */
    public function toArraySingle(Cart $cart = null)
    {
        $model = self::toDefaultArray($cart);
        $model['totalItems'] = is_null($cart) ? 0 : count($cart->getCartItems());
        $model['total'] = is_null($cart) ? 0 : $this->cartService->calculateCartItemsTotal($cart);
        $model['items'] = is_null($cart) ? 0 : $this->cartItemEntityBuilder->toArrayList($cart->getCartItems());
        return $model;
    }
}