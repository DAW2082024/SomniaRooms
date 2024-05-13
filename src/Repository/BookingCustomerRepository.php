<?php

namespace App\Repository;

use App\Entity\BookingCustomer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookingCustomer>
 *
 * @method BookingCustomer|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingCustomer|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingCustomer[]    findAll()
 * @method BookingCustomer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingCustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookingCustomer::class);
    }

    //    /**
    //     * @return BookingCustomer[] Returns an array of BookingCustomer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BookingCustomer
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
