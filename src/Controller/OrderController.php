<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\EntityBuilder\CartEntityBuilder;
use App\EntityBuilder\OrderEntityBuilder;
use App\Repository\OrderRepository;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @var OrderService
     */
    private $orderService;
    /**
     * @var ProductService
     */
    private $productService;
    /**
     * @var CartService
     */
    private $cartService;
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var CartEntityBuilder
     */
    private $cartEntityBuilder;
    /**
     * @var OrderEntityBuilder
     */
    private $orderEntityBuilder;

    /**
     * OrderController constructor.
     * @param OrderService $orderService
     * @param ProductService $productService
     * @param CartService $cartService
     * @param OrderRepository $orderRepository
     * @param CartEntityBuilder $cartEntityBuilder
     * @param OrderEntityBuilder $orderEntityBuilder
     */
    public function __construct(OrderService $orderService, ProductService $productService, CartService $cartService, OrderRepository $orderRepository,
                                CartEntityBuilder $cartEntityBuilder, OrderEntityBuilder $orderEntityBuilder)
    {
        $this->orderService = $orderService;
        $this->productService = $productService;
        $this->cartService = $cartService;
        $this->orderRepository = $orderRepository;
        $this->cartEntityBuilder = $cartEntityBuilder;
        $this->orderEntityBuilder = $orderEntityBuilder;
    }

    /**
     * @Route("/order", name = "order")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function placeOrder()
    {
        /**
         * Get users current cart
         * @var Cart $cart
         */
        $cart = $this->cartService->getCart();

        /**
         * Create order from cart
         * @var Order $order
         */
        $this->orderService->createOrderFromCart($cart);
        $this->cartService->clearCart($cart);

        return $this->redirect('/?success=order_created');
    }

    /**
     * @Route("/order/{orderId}/view", name = "view_order")
     * @param int $orderId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function viewOrder(int $orderId)
    {
        /**
         * Get order by order id
         * @var Order $order
         */
        $order = $this->orderRepository->findOneBy(['id' => $orderId]);

        /** Render cart page twig template with cart model */
        return $this->render('order/index.html.twig', [
            'cart' => $this->cartEntityBuilder->toArraySingle($this->cartService->getCart()),
            'order' => $this->orderEntityBuilder->toArraySingle($order),
        ]);
    }
}
