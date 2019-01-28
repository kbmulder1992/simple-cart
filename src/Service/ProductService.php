<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * ProductService constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param Product $product
     * @return Product
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Product $product): Product
    {
        return $this->productRepository->save($product);
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->productRepository->findAll();
    }

    /**
     * @param int $id
     * @return Product
     */
    public function findOne(int $id): Product
    {
        return $this->productRepository->find($id);
    }

}