<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Exception;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    /**
     * OrderRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @param Order $order
     * @return Order
     * @throws Exception
     */
    public function save(Order $order): Order
    {
        try {
            /**
             * If id is not null then set updated date
             * else set created date
             */
            if ( ! is_null($order->getId())) {
                $order->setUpdatedDate(new \DateTime());
            } else {
                $order->setCreatedDate(new \DateTime());
            }
            $this->getEntityManager()->persist($order);
            $this->getEntityManager()->flush();
            return $order;
        } catch (Exception $exception) {
            throw new Exception("Failed to save order to database. Error: " . $exception->getMessage());
        }
    }

}
