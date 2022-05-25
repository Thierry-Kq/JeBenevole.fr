<?php

namespace App\Repository;

use App\Entity\AnonymizationAsked;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnonymizationAsked|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnonymizationAsked|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnonymizationAsked[]    findAll()
 * @method AnonymizationAsked[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnonymizationAskedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnonymizationAsked::class);
    }
}
