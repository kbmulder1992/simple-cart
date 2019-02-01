<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass = "App\Repository\OrderRepository")
 * @ORM\Table(name = "`order`")
 */
class Order extends AbstractEntity
{

    /**
     * @ORM\Column(type="integer")
     * @var float $total
     */
    private $total;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderItem", mappedBy="order", cascade = {"persist"})
     * @var OrderItem[] $orderItems
     */
    private $orderItems = [];

    /**
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param float $total
     * @return Order
     */
    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * @param OrderItem[] $orderItems
     * @return Order
     */
    public function setOrderItems(?array $orderItems): self
    {
        $this->orderItems = $orderItems;

        return $this;
    }
}
