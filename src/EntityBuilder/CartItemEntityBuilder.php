<?php

namespace App\EntityBuilder;

use App\Entity\CartItem;
use App\Service\CartService;
use App\Service\ProductService;

class CartItemEntityBuilder extends AbstractEntityBuilder
{
    /**
     * @var CartService
     */
    private $cartService;
    /**
     * @var ProductService
     */
    private $productService;
    /**
     * @var ProductEntityBuilder
     */
    private $productEntityBuilder;

    /**
     * CartItemEntityBuilder constructor.
     * @param CartService $cartService
     * @param ProductService $productService
     * @param ProductEntityBuilder $productEntityBuilder
     */
    public function __construct(CartService $cartService, ProductService $productService, ProductEntityBuilder $productEntityBuilder)
    {
        $this->cartService = $cartService;
        $this->productService = $productService;
        $this->productEntityBuilder = $productEntityBuilder;
    }

    /**
     * @param array $cartItems
     * @return array
     * @throws \Exception
     */
    public function toArrayList($cartItems = []): array
    {
        $cartItemsArray = [];
        /** @var CartItem $cartItem */
        foreach ($cartItems as $cartItem) {
            $cartItemsArray[] = $this->toArraySingle($cartItem);
        }

        return $cartItemsArray;
    }

    /**
     * @param CartItem|null $cartItem
     * @return array
     * @throws \Exception
     */
    public function toArraySingle(CartItem $cartItem = null)
    {
        return array_merge(self::toDefaultArray($cartItem), [
            'quantity' => $cartItem->getQuantity(),
            'total' => $this->cartService->calculateSingleCartItemTotal($cartItem),
            'product' => $this->productEntityBuilder->toArraySingle($this->productService->findOne($cartItem->getProductId()))
        ]);
    }
}