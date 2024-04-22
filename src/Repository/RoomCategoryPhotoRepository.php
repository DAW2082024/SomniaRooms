<?php

namespace App\Repository;

use App\Entity\RoomCategoryPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomCategoryPhoto>
 *
 * @method RoomCategoryPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomCategoryPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomCategoryPhoto[]    findAll()
 * @method RoomCategoryPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomCategoryPhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomCategoryPhoto::class);
    }

    //    /**
    //     * @return RoomCategoryPhoto[] Returns an array of RoomCategoryPhoto objects
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

    //    public function findOneBySomeField($value): ?RoomCategoryPhoto
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
