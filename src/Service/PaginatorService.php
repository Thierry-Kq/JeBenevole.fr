<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorService
{
    public function paginate($query, $resultByPage, $currentPage)
    {
        $paginator = new Paginator($query);

        $totalPage = (int) ceil($paginator->count() / $resultByPage);

        $firstResult = ($currentPage * $resultByPage) - $resultByPage;

        $paginatorResult = [
            'items' => $paginator->getQuery()->getResult(),
            'previous' => $firstResult - $resultByPage,
            'current_page' => $currentPage,
            'previous_page' => $currentPage - 1,
            'next_page' => $currentPage + 1,
            'totalPage' => $totalPage,
        ];
        return $paginatorResult;
    }
}