<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    /**
     * ProductRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param Product $product
     * @return Product
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Product $product): Product
    {
        try {
            /**
             * If id is not null then set updated date
             * else set created date
             */
            if ( ! is_null($product->getId())) {
                $product->setUpdatedDate(new \DateTime());
            } else {
                $product->setCreatedDate(new \DateTime());
            }
            $this->getEntityManager()->persist($product);
            $this->getEntityManager()->flush();
            return $product;
        } catch (Exception $exception) {
            throw new Exception("Failed to save product to database. Error: " . $exception->getMessage());
        }
    }
}
