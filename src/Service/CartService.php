<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Config\Definition\Exception\Exception;

class CartService
{
    /**
     * @var CartRepository
     */
    private $cartRepository;
    /**
     * @var CartItemRepository
     */
    private $cartItemRepository;
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * CartService constructor.
     * @param CartRepository $cartRepository
     * @param CartItemRepository $cartItemRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(CartRepository $cartRepository, CartItemRepository $cartItemRepository, ProductRepository $productRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Cart $cart
     * @return Cart
     * @throws \Exception
     */
    public function save(Cart $cart): Cart
    {
        return $this->cartRepository->save($cart);
    }

    /**
     * @return Cart
     * @throws \Exception
     */
    public function getCart(): Cart
    {
        /** @var Cart $cart */
        $cart = $this->cartRepository->findOneBy(['userId' => 1]);

        /** Create new cart if none exist */
        if (is_null($cart)) {
            $cart = new Cart();
            $cart->setUserId(1);
            try {
                $cart = $this->save($cart);
            } catch (Exception $exception) {
                throw new Exception("Could not create new cart.", $exception);
            }
        }

        return $cart;
    }

    /**
     * @param Cart $cart
     * @return float
     */
    public function calculateCartItemsTotal(Cart $cart): float
    {
        $total = 0;
        /** @var int $cartItem */
        foreach ($cart->getCartItems() as $cartItemId) {
            /** @var CartItem $cartItem */
            $cartItem = $this->cartRepository->find($cartItemId);

            /** @var Product $product */
            $product = $this->productRepository->find($cartItem->getProductId());

            /** Up total value with product price */
            $total += $product->getPrice();
        }
        return $total;
    }

    /**
     * @param Cart $cart
     * @param int $productId
     * @return Cart
     * @throws \Exception
     */
    public function addProductToCart(Cart $cart, int $productId): Cart
    {
        /**
         * Get existing cart item from cart
         * @var $existingCartItem
         */
        $existingCartItem = $this->getExistingCartItemByProductId($cart, $productId);

        /** If cart item doesn't exist then create one */
        if (is_null($existingCartItem)) {
            $cartItem = new CartItem();
            $cartItem->setCartId($cart->getId());
            $cartItem->setProductId($productId);
            $cartItem->setQuantity(1);
        } else {
            /** Otherwise update quantity of existing cart item */
            $cartItem = $existingCartItem->setQuantity($existingCartItem->getQuantity() + 1);
        }

        $this->cartItemRepository->save($cartItem);

        return $this->cartRepository->find($cart->getId());
    }

    /**
     * @param Cart $cart
     * @param int $productId
     * @return Cart
     * @throws \Exception
     */
    public function removeOneProductFromCart(Cart $cart, int $productId): Cart
    {
        /**
         * Get existing cart item from cart
         * @var $existingCartItem
         */
        $existingCartItem = $this->getExistingCartItemByProductId($cart, $productId);

        /** ensure cart item does not exist before trying to apply updates */
        if ( ! is_null($existingCartItem)) {
            /** If cart item quantity is 1 then delete it */
            if ($existingCartItem->getQuantity() === 1) {
                $cartItem = $existingCartItem->setQuantity(0);
            } else {
                /** Otherwise update quantity of existing cart item */
                $cartItem = $existingCartItem->setQuantity($existingCartItem->getQuantity() + 1);
            }

            $this->cartItemRepository->save($cartItem);
        }

        return $this->cartRepository->find($cart->getId());
    }

    /**
     * @param Cart $cart
     * @param int $productId
     * @return CartItem|null
     */
    private function getExistingCartItemByProductId(Cart $cart, int $productId)
    {
        /** @var CartItem $cartItem */
        $existingCartItem = null;
        foreach ($cart->getCartItems() as $cartItemId) {
            if (is_null($existingCartItem)) {
                /** @var CartItem $cartItem */
                $cartItem = $this->cartRepository->find($cartItemId);
                if ($cartItem->getProductId() === $productId) {
                    $existingCartItem = $cartItem;
                }
            }
        }
        return $existingCartItem;
    }
}