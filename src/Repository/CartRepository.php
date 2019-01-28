<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Exception;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    /**
     * @param Cart $cart
     * @return Cart
     * @throws Exception
     */
    public function save(Cart $cart): Cart
    {
        try {
            /**
             * If id is not null then set updated date
             * else set created date
             */
            if ( ! is_null($cart->getId())) {
                $cart->setUpdatedDate(new \DateTime());
            } else {
                $cart->setCreatedDate(new \DateTime());
            }
            $this->getEntityManager()->persist($cart);
            $this->getEntityManager()->flush();
            return $cart;
        } catch (Exception $exception) {
            throw new Exception("Failed to save cart to database. Error: " . $exception->getMessage(), $exception);
        }
    }
}
