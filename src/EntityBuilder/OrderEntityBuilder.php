<?php

namespace App\EntityBuilder;

use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Service\CartService;
use App\Service\ProductService;

class OrderEntityBuilder extends AbstractEntityBuilder
{
    /**
     * @var ProductService
     */
    private $productService;
    /**
     * @var ProductEntityBuilder
     */
    private $productEntityBuilder;

    /**
     * OrderEntityBuilder constructor.
     * @param ProductService $productService
     * @param ProductEntityBuilder $productEntityBuilder
     */
    public function __construct(ProductService $productService, ProductEntityBuilder $productEntityBuilder)
    {
        $this->productService = $productService;
        $this->productEntityBuilder = $productEntityBuilder;
    }

    /**
     * @param array $orders
     * @return array
     * @throws \Exception
     */
    public function toArrayList($orders = []): array
    {
        $ordersArray = [];
        /** @var Order $order */
        foreach ($orders as $order) {
            $ordersArray[] = $this->toArraySingle($order);
        }

        return self::toListArray($ordersArray);
    }

    /**
     * @param Order|null $order
     * @return array
     * @throws \Exception
     */
    public function toArraySingle(Order $order = null)
    {
        /** Get products for order */
        $products = [];
        /** @var OrderItem $orderItem */
        foreach ($order->getOrderItems() as $orderItem) {
            $products[] = array_merge($this->productEntityBuilder->toArraySingle($this->productService->findOne($orderItem->getProductId())), ['quantity' => $orderItem->getQuantity()]);
        }
        return array_merge(self::toDefaultArray($order), [
            'totalItems' => count($order->getOrderItems()),
            'total' => $order->getTotal(),
            'products' => $products
        ]);
    }
}