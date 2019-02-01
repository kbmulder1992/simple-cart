<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Config\Definition\Exception\Exception;

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
     * @param int $sampleProductsCount
     * @return Product[]|array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function initialiseDefaultData(int $sampleProductsCount = 10): array
    {
        try {
            /** @var Product[] $productsGenerated */
            $productsGenerated = [];
            /**
             * Generate sample products based on sample products count parameter
             */
            for ($i = 1; $i <= $sampleProductsCount; ++$i) {
                $product = new Product();
                $product->setName('Sample Product #' . $i);
                $product->setPrice(23 * $i);
                $product = $this->save($product);
                $productsGenerated[] = $product;
            }

            return $productsGenerated;
        } catch (Exception $exception) {
            throw new Exception("Attempted creating default product data but failed. Error: " . $exception->getMessage(), $exception);
        }
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