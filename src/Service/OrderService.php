<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\OrderRepository;

class OrderService
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var CartService
     */
    private $cartService;

    /**
     * CartService constructor.
     * @param OrderRepository $orderRepository
     * @param CartService $cartService
     */
    public function __construct(OrderRepository $orderRepository, CartService $cartService)
    {
        $this->orderRepository = $orderRepository;
        $this->cartService = $cartService;
    }
    /**
     * @param Order $order
     * @return Order
     * @throws \Exception
     */
    public function save(Order $order): Order
    {
        return $this->orderRepository->save($order);
    }

    /**
     * @param Cart|null $cart
     * @return Order
     * @throws \Exception
     */
    public function createOrderFromCart(Cart $cart = null): Order
    {
        /** If cart is null then get current cart */
        if (is_null($cart)) {
            $cart = $this->cartService->getCart();
        }

        /** Create new order */
        $order = new Order();
        $order->setTotal($this->cartService->calculateCartItemsTotal($cart));
        $order = $this->save($order);
        $order = $this->save($order->setOrderItems($this->getOrderItemsFromCartItems($cart, $order)));
        return $order;
    }

    /**
     * @param Cart $cart
     * @param Order $order
     * @return OrderItem[]|array
     */
    private function getOrderItemsFromCartItems(Cart $cart, Order $order)
    {
        /** @var OrderItem[] $orderItems */
        $orderItems = [];
        /** @var CartItem $cartItem */
        foreach ($cart->getCartItems() as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->setCreatedDate(new \DateTime());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setProductId($cartItem->getProductId());
            $orderItem->setOrder($order);

            /** Add order item to array */
            $orderItems[] = $orderItem;
        }
        return $orderItems;
    }
}
