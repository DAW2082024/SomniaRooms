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

    /**
     * Gets all fareTables for time period.
     * @param integer|string ID Categoria.
     * @param DateTimeInterface Period start date
     * @param DateTimeInterface Period end date
     * @return array Array with all faretables active in given period.
     */
    public function findFareTable(int $roomCategory, \DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        $strStartDate = $startDate->format('Y-m-d');
        $strEndDate = $endDate->format('Y-m-d');

        return $this->createQueryBuilder('f')
            ->andWhere('f.roomCategory = :category')
            ->andWhere('f.startDate < :endDate')
            ->andWhere('f.endDate >= :startDate')
            ->setParameter('category', $roomCategory)
            ->setParameter('startDate', $strStartDate)
            ->setParameter('endDate', $strEndDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * Obtiene una lista con los números de huéspedes permitidos por todos los tarifarios pasados.
     * @param FareTable[] $fareTableList Lista con los FareTable a consultar.
     * @return int[] Lista con los números de huéspedes permitidos.
     */
    public function getAllowedGuestNumberOnFareTableList(array $fareTableList): array
    {
        $fareTableCount = count($fareTableList);
        if($fareTableCount == 0) {
            return [];
        }

        $fareTableIdList = \array_map(function (FareTable $element) {
            return $element->getId();
        }, $fareTableList);
        $strIdList = \implode(",", $fareTableIdList);

        //Subquery -> Obtiene los números de huéspedes de las tarifas de un tarifario.
        //Query -> Cuenta en cuantos tarifarios aparece cada número de huéspedes y queda con aquellos que aparecen en todos (having).
        $sql = "SELECT guest_number
                    FROM (SELECT DISTINCT fare_table_id, guest_number FROM room_fare WHERE fare_table_id IN ($strIdList))
                GROUP BY guest_number
                HAVING COUNT(fare_table_id) = $fareTableCount";

        $conn = $this->getEntityManager()->getConnection();
        $rs = $conn->executeQuery($sql);

        $allowedGuestNumbers = [];
        while (true) {
            $value = $rs->fetchOne();
            if (!$value) {
                break;
            }
            $allowedGuestNumbers[] = $value;
        }

        return $allowedGuestNumbers;
    }
}
