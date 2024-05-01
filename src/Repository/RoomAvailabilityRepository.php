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


    /**
     * @param \DateTimeInterface Period start (included in range).
     * @param \DateTimeInterface Period end (not included in range).
     * @return RoomAvailability[] Returns an array of RoomAvailability objects
     */
    public function getAvailabilityForPeriod(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        $strStartDate = $startDate->format('Y-m-d');
        $strEndDate = $endDate->format('Y-m-d');

        return $this->createQueryBuilder('ra')
            ->select('IDENTITY(ra.roomCategory) as roomCategory, MIN(ra.numAvailable) as availability')
            ->andWhere('ra.day >= :startDate')
            ->andWhere('ra.day < :endDate')
            ->groupBy('ra.roomCategory')
            ->setParameter('startDate', $strStartDate)
            ->setParameter('endDate', $strEndDate)
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function getAvailabilityForPeriodDetails(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        $strStartDate = $startDate->format('Y-m-d');
        $strEndDate = $endDate->format('Y-m-d');

        return $this->createQueryBuilder('ra')
            ->andWhere('ra.day >= :startDate')
            ->andWhere('ra.day < :endDate')
            ->setParameter('startDate', $strStartDate)
            ->setParameter('endDate', $strEndDate)
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
