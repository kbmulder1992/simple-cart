<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderItemRepository")
 */
class OrderItem extends AbstractEntity
{

    /**
     * @ORM\ManyToOne(targetEntity = "App\Entity\Order", inversedBy = "orderItems", cascade = {"persist"})
     * @var Order $order
     */
    private $order;

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
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @param Order $order
     * @return OrderItem
     */
    public function setOrder(Order $order): self
    {
        $this->order = $order;

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
