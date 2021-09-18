<?php

namespace App\Repository;

use App\Entity\Categories;
use App\Service\PaginatorService;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Categories|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categories|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categories[]    findAll()
 * @method Categories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesRepository extends ServiceEntityRepository
{
    private $paginatorService;

    public function __construct(
        ManagerRegistry $registry,
        PaginatorService $paginatorService
    ) {
        parent::__construct($registry, Categories::class);
        $this->paginatorService = $paginatorService;
    }

    /**
     * @return Paginator Returns an instance of paginator
     */
    public function findAllCategories(int $page, int $resultByPage = 10): array
    {
        $firstResult = ($page * $resultByPage) - $resultByPage;

        $query = $this->createQueryBuilder('a')
        ->andWhere('a.isDeleted = :val')
            ->setParameter('val', 0)
            ->setMaxResults($resultByPage)
            ->setFirstResult($firstResult)
            ->getQuery();

       return $this->paginatorService->paginate($query, $resultByPage, $page);
    }

}
