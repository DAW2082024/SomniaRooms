<?php

namespace App\Repository;

use App\Entity\RoomAvailability;
use App\Entity\RoomCategory;
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
            ->getArrayResult();
    }

    /**
     * @param \DateTimeInterface Period start (included in range).
     * @param \DateTimeInterface Period end (not included in range).
     * @return int Returns number of available room of that category on selected period.
     */
    public function getAvailabilityForCategoryInPeriod(RoomCategory $roomCategory, \DateTimeInterface $startDate, \DateTimeInterface $endDate): int|null
    {
        $strStartDate = $startDate->format('Y-m-d');
        $strEndDate = $endDate->format('Y-m-d');
        $roomCatId = $roomCategory->getId();

        return $this->createQueryBuilder('ra')
            ->select('MIN(ra.numAvailable) as availability')
            ->andWhere('ra.day >= :startDate')
            ->andWhere('ra.day < :endDate')
            ->andWhere('ra.roomCategory = :roomCategory')
            ->setParameter('roomCategory', $roomCatId)
            ->setParameter('startDate', $strStartDate)
            ->setParameter('endDate', $strEndDate)
            ->getQuery()
            ->getSingleScalarResult();
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
            ->getArrayResult();
    }

    /**
     * Updates All Availability of selected roomCategory during period. It can operate in 2 modes, normal and diff mode.
     * - Normal mode -> Sets value.
     * - Diff mode -> apply value as sum ( currentAvailability += diff )
     * NOTE: A Flush is needed after this operation.
     */
    public function updateAvailabilityForRoomCategoryOnPeriod(RoomCategory $roomCategory, \DateTimeInterface $startDate, \DateTimeInterface $endDate, int $amount, bool $isDiff = false): void
    {
        $strStartDate = $startDate->format('Y-m-d');
        $strEndDate = $endDate->format('Y-m-d');
        $roomCatId = $roomCategory->getId();

        $availabilityItemList = $this->createQueryBuilder('ra')
            ->andWhere('ra.day >= :startDate')
            ->andWhere('ra.day < :endDate')
            ->andWhere('ra.roomCategory = :roomCategory')
            ->setParameter('roomCategory', $roomCatId)
            ->setParameter('startDate', $strStartDate)
            ->setParameter('endDate', $strEndDate)
            ->getQuery()
            ->getResult();

        $em = $this->getEntityManager();

        foreach ($availabilityItemList as $item) {
            if ($isDiff) {
                $item->modNumAvailable($amount);
            } else {
                $item->setNumAvailable($amount);
            }
        }
    }
}
