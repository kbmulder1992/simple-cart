<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartItemRepository")
 */
class CartItem extends AbstractEntity
{

    /**
     * @ORM\ManyToOne(targetEntity = "App\Entity\Cart", inversedBy = "cartItems", cascade = {"persist"})
     * @var Cart $cart
     */
    private $cart;

    /**
     * @ORM\Column(type="integer")
     * @var int $productId
     */
    private $productId;

    /**
     * @ORM\Column(type="integer")
     * @var int $quantity
     */
    private $quantity;

    /**
     * @return Cart|null
     */
    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     * @return CartItem
     */
    public function setCart(Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     * @return CartItem
     */
    public function setProductId(int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return CartItem
     */
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
