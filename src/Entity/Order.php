<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 */
class Order extends AbstractEntity
{

    /**
     * @ORM\Column(type="integer")
     * @var boolean $total
     */
    private $total;

    /**
     * @ORM\Column(type="array")
     * @var Product[] $products
     */
    private $products = [];

    /**
     * @return bool|null
     */
    public function getTotal(): ?bool
    {
        return $this->total;
    }

    /**
     * @param bool $total
     * @return Order
     */
    public function setTotal(bool $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getProducts(): ?array
    {
        return $this->products;
    }

    /**
     * @param array $products
     * @return Order
     */
    public function setProducts(array $products): self
    {
        $this->products = $products;

        return $this;
    }
}
