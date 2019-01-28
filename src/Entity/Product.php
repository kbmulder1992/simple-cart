<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Serializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product extends AbstractEntity
{

    /**
     * @ORM\Column(type="string", length=255)
     * @var string $name
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @var boolean
     */
    private $price;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Product
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }
}
