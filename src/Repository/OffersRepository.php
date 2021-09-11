<?php

namespace App\Repository;

use App\Entity\Offers;
use App\Entity\Users;
use App\Service\PaginatorService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offers[]    findAll()
 * @method Offers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffersRepository extends ServiceEntityRepository
{
    private $paginatorService;

    public function __construct(
        ManagerRegistry $registry,
        PaginatorService $paginatorService
    )
    {
        parent::__construct($registry, Offers::class);
        $this->paginatorService = $paginatorService;
    }

    /**
     * @return Paginator Returns an instance of paginator
     */
    public function findAllOffersOrRequests(int $page, string $route, int $resultByPage = 10): array
    {
        $firstResult = ($page * $resultByPage) - $resultByPage;

        $query = $this->createQueryBuilder('o')
            ->andWhere('o.isDeleted = :val')
            ->setParameter('val', 0);

        if ($route === 'requests')
        {
            $query
                ->andWhere('o.users IS NOT NULL')
                ->innerJoin('o.users', 'users')
                ->addSelect('o.title, o.address, o.zip, o.description, o.slug, users.firstName, users.lastName');
        } elseif ($route === 'offers' )
        {
            $query
                ->andWhere('o.associations IS NOT NULL')
                ->innerJoin('o.associations', 'associations')
                ->addSelect('o.title, o.address, o.zip, o.description, o.slug, associations.name');
        }
        $query
            ->setMaxResults($resultByPage)
            ->setFirstResult($firstResult)
            ->getQuery();

        return $this->paginatorService->paginate($query, $resultByPage, $page);
    }
}
