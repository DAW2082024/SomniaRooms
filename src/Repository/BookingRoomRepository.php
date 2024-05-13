<?php

namespace App\Repository;

use App\Entity\BookingRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookingRoom>
 *
 * @method BookingRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingRoom[]    findAll()
 * @method BookingRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookingRoom::class);
    }

    //    /**
    //     * @return BookingRoom[] Returns an array of BookingRoom objects
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

    //    public function findOneBySomeField($value): ?BookingRoom
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
