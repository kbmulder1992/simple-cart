<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartRepository")
 */
class Cart extends AbstractEntity
{

    /**
     * @ORM\Column(type="integer")
     * @var int $userId
     */
    private $userId;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @var CartItem[] $cartItems
     */
    private $cartItems = [];

    /** @var int $cartTotal */
    private $cartTotal = 0;

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Cart
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getCartItems(): ?array
    {
        return $this->cartItems;
    }

    /**
     * @param array|null $cartItems
     * @return Cart
     */
    public function setCartItems(?array $cartItems): self
    {
        $this->cartItems = $cartItems;

        return $this;
    }

    /**
     * @return int
     */
    public function getCartTotal(): int
    {
        return $this->cartTotal;
    }

    /**
     * @param int $cartTotal
     */
    public function setCartTotal(int $cartTotal): void
    {
        $this->cartTotal = $cartTotal;
    }

}
