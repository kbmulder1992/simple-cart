<?php

namespace App\Repository;

use App\Entity\CartItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Exception;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CartItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartItem[]    findAll()
 * @method CartItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartItemRepository extends ServiceEntityRepository
{
    /**
     * CartItemRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CartItem::class);
    }

    /**
     * @param CartItem $cartItem
     * @return CartItem
     * @throws Exception
     */
    public function save(CartItem $cartItem): CartItem
    {
        try {
            /**
             * If id is not null then set updated date
             * else set created date
             */
            if ( ! is_null($cartItem->getId())) {
                $cartItem->setUpdatedDate(new \DateTime());
            } else {
                $cartItem->setCreatedDate(new \DateTime());
            }
            $this->getEntityManager()->persist($cartItem);
            $this->getEntityManager()->flush();
            return $cartItem;
        } catch (Exception $exception) {
            throw new Exception("Failed to save cart item to database. Error: " . $exception->getMessage());
        }
    }

    /**
     * @param CartItem $cartItem
     * @throws Exception
     */
    public function delete(CartItem $cartItem): void
    {
        try {
            $this->getEntityManager()->remove($cartItem);
            $this->getEntityManager()->flush();
        } catch (Exception $exception) {
            throw new Exception("Failed to delete cart item from database. Error: " . $exception->getMessage());
        }
    }

}
