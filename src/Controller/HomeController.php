<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\EntityBuilder\CartEntityBuilder;
use App\EntityBuilder\OrderEntityBuilder;
use App\EntityBuilder\ProductEntityBuilder;
use App\Repository\OrderRepository;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @var ProductService $productService
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
     * @var ProductEntityBuilder $productEntityBuilder
     */
    private $productEntityBuilder;
    /**
     * @var CartEntityBuilder
     */
    private $cartEntityBuilder;
    /**
     * @var OrderEntityBuilder
     */
    private $orderEntityBuilder;

    /**
     * HomeController constructor.
     * @param ProductService $productService
     * @param CartService $cartService
     * @param OrderRepository $orderRepository
     * @param ProductEntityBuilder $productEntityBuilder
     * @param CartEntityBuilder $cartEntityBuilder
     * @param OrderEntityBuilder $orderEntityBuilder
     */
    public function __construct(ProductService $productService, CartService $cartService, OrderRepository $orderRepository,
                                ProductEntityBuilder $productEntityBuilder, CartEntityBuilder $cartEntityBuilder, OrderEntityBuilder $orderEntityBuilder)
    {
        $this->productService = $productService;
        $this->cartService = $cartService;
        $this->orderRepository = $orderRepository;
        $this->productEntityBuilder = $productEntityBuilder;
        $this->cartEntityBuilder = $cartEntityBuilder;
        $this->orderEntityBuilder = $orderEntityBuilder;
    }

    /**
     * @Route("/", name = "home")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function home()
    {
        /**
         * Get products for home page rendering
         * @var Product[] $products
         */
        $products = $this->productService->findAll();

        /**
         * Get users current cart
         * @var Cart $cart
         */
        $cart = $this->cartService->getCart();

        /**
         * Convert to model for view
         * @var array $model
         */
        $model = [
            'cart' => $this->cartEntityBuilder->toArraySingle($cart),
            'products' => $this->productEntityBuilder->toArrayList($products),
            'message' => $this->getErrorOrSuccessMessage(),
            'orders' => $this->orderEntityBuilder->toArrayList($this->orderRepository->findAll())
        ];

        /** Render home page twig template with products model */
        return $this->render('home.html.twig', $model);
    }

    /**
     * @Route("/initialise-product-data", name = "product_data_initialiser")
     * @Route("/initialise-product-data/{limit}", name = "product_data_initialiser_with_limit")
     * @param int $limit
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function productDataInitialiser(int $limit = 25)
    {
        /** @var Product[] $productsGenerated */
        $productsGenerated = $this->productService->initialiseDefaultData($limit);
        return $this->json($this->productEntityBuilder->toArrayList($productsGenerated));
    }

    /**
     * @return array
     */
    private function getErrorOrSuccessMessage(): array
    {
        $message = array_key_exists('success', $_GET) && isset($_GET['success'])
            ? 'Success: ' . $_GET['success']
            : (array_key_exists('error', $_GET) && isset($_GET['error'])
                ? 'Error: '. $_GET['error']
                : '');

        return [
            'valid' => array_key_exists('success', $_GET) || array_key_exists('error', $_GET),
            'type' => array_key_exists('success', $_GET)  ? 'success' : 'error',
            'value' => $message
        ];
    }

}
