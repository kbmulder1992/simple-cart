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
        /** @var CartItem $cartItem */
        foreach ($cart->getCartItems() as $cartItem) {
            /** @var Product $product */
            $product = $this->productRepository->find($cartItem->getProductId());
            /** Up total value with product price */
            $total += ($product->getPrice() * $cartItem->getQuantity());
        }
        return $total;
    }

    /**
     * @param CartItem $cartItem
     * @param Product|null $product
     * @return float
     * @throws \Exception
     */
    public function calculateSingleCartItemTotal(CartItem $cartItem, Product $product = null): float
    {
        /** If product is null then get it from database */
        if (is_null($product)) {
            $product = $this->productRepository->findOneBy(['id' => $cartItem->getProductId()]);
            /** If product is still null, then delete cart item as product no longer exists */
            if (is_null($product)) {
                $this->cartItemRepository->delete($cartItem);
                return 0;
            }
        }

        /** Calculate product price times by cart item quantity */
        $total = $product->getPrice() * $cartItem->getQuantity();

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
            $cartItem->setCart($cart);
            $cartItem->setProductId($productId);
            $cartItem->setQuantity(1);
        } else {
            /** Otherwise update quantity of existing cart item */
            $cartItem = $existingCartItem->setQuantity($existingCartItem->getQuantity() + 1);
        }

        $this->cartItemRepository->save($cartItem);
        $this->cartRepository->save($cart);

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

        /** ensure cart item does exist before trying to apply updates */
        if ( ! is_null($existingCartItem)) {
            /** If cart item quantity is 1 then delete it */
            if ($existingCartItem->getQuantity() === 1) {
                $this->cartItemRepository->delete($existingCartItem);
            } else {
                /** Otherwise update quantity of existing cart item */
                $cartItem = $existingCartItem->setQuantity($existingCartItem->getQuantity() - 1);
                /** Save quantity to database */
                $this->cartItemRepository->save($cartItem);
            }

        }

        return $this->cartRepository->find($cart->getId());
    }

    /**
     * @param Cart $cart
     * @param int $productId
     * @return Cart
     * @throws \Exception
     */
    public function removeAllProductFromCart(Cart $cart, int $productId): Cart
    {
        /**
         * Get existing cart item from cart
         * @var $existingCartItem
         */
        $existingCartItem = $this->getExistingCartItemByProductId($cart, $productId);

        /** ensure cart item does exist before trying to delete */
        if ( ! is_null($existingCartItem)) {
            $this->cartItemRepository->delete($existingCartItem);
        }

        return $this->cartRepository->find($cart->getId());
    }

    /**
     * @param Cart $cart
     * @return Cart
     * @throws \Exception
     */
    public function clearCart(Cart $cart = null): Cart
    {
        /** If cart is null then get current cart */
        if (is_null($cart)) {
            $cart = $this->getCart();
        }

        /** @var CartItem $cartItem */
        foreach ($cart->getCartItems() as $cartItem) {
            $this->cartItemRepository->delete($cartItem);
        }

        return $this->getCart();
    }

    /**
     * @param Cart $cart
     * @param int $productId
     * @return CartItem|null
     */
    private function getExistingCartItemByProductId(Cart $cart, int $productId)
    {
        $existingCartItem = null;
        /** @var CartItem $cartItem */
        foreach ($cart->getCartItems() as $cartItem) {
            if (is_null($existingCartItem)) {
                if ($cartItem->getProductId() === $productId) {
                    $existingCartItem = $cartItem;
                }
            }
        }
        return $existingCartItem;
    }
}