<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedDate;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTimeInterface $createdDate
     * @return AbstractEntity
     */
    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedDate(): ?\DateTimeInterface
    {
        return $this->updatedDate;
    }

    /**
     * @param \DateTimeInterface|null $updatedDate
     * @return AbstractEntity
     */
    public function setUpdatedDate(?\DateTimeInterface $updatedDate): self
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }
}
