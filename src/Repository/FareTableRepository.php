<?php

namespace App\Repository;

use App\Entity\FareTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FareTable>
 *
 * @method FareTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method FareTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method FareTable[]    findAll()
 * @method FareTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FareTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FareTable::class);
    }

//    /**
//     * @return FareTable[] Returns an array of FareTable objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FareTable
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
