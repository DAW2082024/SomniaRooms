<?php

namespace App\Repository;

use App\Entity\RoomAvailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomAvailability>
 *
 * @method RoomAvailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomAvailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomAvailability[]    findAll()
 * @method RoomAvailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomAvailability::class);
    }

    /**
     * @return RoomAvailability[] Returns an array of RoomAvailability objects
     */
    public function findByRoomCategory($categoryId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.roomCategory = :val')
            ->setParameter('val', $categoryId)
            ->orderBy('a.day', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?RoomAvailability
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
