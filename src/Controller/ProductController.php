<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\EntityBuilder\PageModelBuilder;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var CartService
     */
    private $cartService;

    /**
     * ProductController constructor.
     * @param ProductRepository $productRepository
     * @param CartRepository $cartRepository
     * @param CartService $cartService
     */
    public function __construct(ProductRepository $productRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }


    /**
     * @Route("/product/{productId}/add-to-cart", name = "product_add_to_cart")
     * @param int $productId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function addProductToCart(int $productId)
    {
        /**
         * Find product
         * @var Product $product
         */
        $product = $this->productRepository->find($productId);

        /** If no product redirect with error */
        if (is_null($product)) {
            return $this->redirect('/?error=no_product');
        }

        /**
         * Get users cart
         * @var Cart $cart
         */
        $cart = $this->cartService->getCart();

        $this->cartService->addProductToCart($cart, $productId);

        return $this->redirect('/?success=product_added');
    }
}
