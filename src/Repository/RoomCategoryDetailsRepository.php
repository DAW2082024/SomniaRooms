<?php

namespace App\Repository;

use App\Entity\RoomCategoryDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomCategoryDetails>
 *
 * @method RoomCategoryDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomCategoryDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomCategoryDetails[]    findAll()
 * @method RoomCategoryDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomCategoryDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomCategoryDetails::class);
    }

    //    /**
    //     * @return RoomCategoryDetails[] Returns an array of RoomCategoryDetails objects
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

    //    public function findOneBySomeField($value): ?RoomCategoryDetails
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
