<?php

namespace App\Repository;

use App\Entity\Associations;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Associations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Associations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Associations[]    findAll()
 * @method Associations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociationsRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 4;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Associations::class);
    }

    /**
     * @return Paginator Returns an instance of paginator
     */
    public function findAllAssociations(int $offset): Paginator
    {
        $query = $this->createQueryBuilder('a')
            ->andWhere('a.isDeleted = :val')
            ->setParameter('val', 0)
           ->setMaxResults(self::PAGINATOR_PER_PAGE)
           ->setFirstResult($offset)
            ->getQuery()
        ;
        return new Paginator($query);
    }
}
