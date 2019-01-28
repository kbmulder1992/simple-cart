<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartItemRepository")
 */
class CartItem extends AbstractEntity
{

    /**
     * @ORM\Column(type="integer")
     * @var int $cartId
     */
    private $cartId;

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
     * @return int|null
     */
    public function getCartId(): ?int
    {
        return $this->cartId;
    }

    /**
     * @param int $cartId
     * @return CartItem
     */
    public function setCartId(int $cartId): self
    {
        $this->cartId = $cartId;

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
