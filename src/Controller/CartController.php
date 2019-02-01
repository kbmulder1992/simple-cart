<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\EntityBuilder\CartEntityBuilder;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
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
     * @var CartEntityBuilder
     */
    private $cartEntityBuilder;

    /**
     * CartController constructor.
     * @param ProductRepository $productRepository
     * @param CartService $cartService
     * @param CartEntityBuilder $cartEntityBuilder
     */
    public function __construct(ProductRepository $productRepository, CartService $cartService, CartEntityBuilder $cartEntityBuilder)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
        $this->cartEntityBuilder = $cartEntityBuilder;
    }

    /**
     * @Route("/cart", name = "cart")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function view()
    {
        /** Render cart page twig template with cart model */
        return $this->render('cart/index.html.twig', [
            'cart' => $this->cartEntityBuilder->toArraySingle($this->cartService->getCart()),
            'message' => $this->getErrorOrSuccessMessage(),
        ]);
    }

    /**
     * @Route("/cart/add-product/{productId}", name = "product_add_to_cart")
     * @param int $productId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function addProductToCart(int $productId)
    {
        /**
         * @var Product $product
         */
        $product = $this->productRepository->find($productId);
        /** If no product redirect with error */
        if (is_null($product)) {
            return $this->redirect('/cart/?error=no_product');
        }

        /**
         * Get users cart, add to cart and then redirect
         * @var Cart $cart
         */
        $cart = $this->cartService->getCart();
        $this->cartService->addProductToCart($cart, $productId);
        return $this->redirect('/cart/?success=product_added');
    }

    /**
     * @Route("/cart/remove-product/{productId}", name = "product_remove_from_cart")
     * @param int $productId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function removeProductFromCart(int $productId)
    {
        /**
         * @var Product $product
         */
        $product = $this->productRepository->find($productId);
        /** If no product redirect with error */
        if (is_null($product)) {
            return $this->redirect('/cart/?error=no_product');
        }

        /**
         * Get users cart, remove from cart and then redirect
         * @var Cart $cart
         */
        $cart = $this->cartService->getCart();
        $this->cartService->removeOneProductFromCart($cart, $productId);
        return $this->redirect('/cart/?success=cart_item_removed');
    }

    /**
     * @Route("/cart/clear-product/{productId}", name = "product_clear_from_cart")
     * @param int $productId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function clearProductFromCart(int $productId)
    {
        /**
         * @var Product $product
         */
        $product = $this->productRepository->find($productId);
        /** If no product redirect with error */
        if (is_null($product)) {
            return $this->redirect('/cart/?error=no_product');
        }

        /**
         * Get users cart, clear from cart and then redirect
         * @var Cart $cart
         */
        $cart = $this->cartService->getCart();
        $this->cartService->removeAllProductFromCart($cart, $productId);
        return $this->redirect('/cart/?success=cart_item_removed');
    }

    /**
     * @Route("/cart/clear", name = "clear_cart")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function clearCart()
    {
        $this->cartService->clearCart();
        return $this->redirect('/cart/?success=cart_cleared');
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
