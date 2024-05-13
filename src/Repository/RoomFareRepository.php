<?php

namespace App\Repository;

use App\Entity\RoomFare;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomFare>
 *
 * @method RoomFare|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomFare|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomFare[]    findAll()
 * @method RoomFare[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomFareRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomFare::class);
    }

    //    /**
    //     * @return RoomFare[] Returns an array of RoomFare objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RoomFare
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
