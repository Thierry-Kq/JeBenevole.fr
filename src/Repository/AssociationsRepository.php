<?php

namespace App\Repository;

use App\Entity\Associations;
use App\Service\PaginatorService;
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

    private $paginatorService;

    public function __construct(
        ManagerRegistry $registry,
        PaginatorService $paginatorService
    ) {
        parent::__construct($registry, Associations::class);
        $this->paginatorService = $paginatorService;
    }

    /**
     * @return Paginator Returns an instance of paginator
     */
    public function findAllAssociations(int $page, int $resultByPage = 10): array
    {
        $firstResult = ($page * $resultByPage) - $resultByPage;

        $query = $this->createQueryBuilder('a')
            ->andWhere('a.isDeleted = :val')
            ->setParameter('val', 0)
            ->setMaxResults($resultByPage)
            ->setFirstResult($firstResult)
            ->getQuery();

        $data = $this->paginatorService->paginate($query, $resultByPage, $page);


        return $data;
    }
}
