<?php

namespace App\Repository;

use App\Entity\ConfigVariable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConfigVariable>
 *
 * @method ConfigVariable|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigVariable|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigVariable[]    findAll()
 * @method ConfigVariable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigVariableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigVariable::class);
    }

    /**
     * Get all config variables for API.
     */
    public function findAllVariables(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.key, c.value, c.section')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string Config section.
     * @return ConfigVariable[] Returns an array of ConfigVariable objects
     */
    public function findAllBySection(string $section): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.key, c.value')
            ->andWhere('c.section = :val')
            ->setParameter('val', $section)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get Config Variable by it's key.
     * @param string Variable Key
     * @return ConfigVariable|null Config Variable.
     */
    public function findVariableByKey($varKey)
    {
        return $this->createQueryBuilder('c')
            ->select('c.key, c.value, c.section')
            ->andWhere('c.key = :val')
            ->setParameter('val', $varKey)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get all section names that contains config variables.
     * @return string[] Returns an array of strings.
     */
    public function getAllSections(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.section')
            ->distinct()
            ->getQuery()
            ->getResult();
    }
}
