<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginatorService
{
    public function paginate($query, $resultByPage, $currentPage)
    {
        $paginator = new Paginator($query);

        $totalPage = (int) ceil($paginator->count() / $resultByPage);

        // todo : rewrite url if wrong page number ?
        // verify the page isnt > than last page
        $currentPage = $currentPage > $totalPage ? 1 : $currentPage;

        $firstResult = ($currentPage * $resultByPage) - $resultByPage;

        $paginatorResult = [
            'items' => $paginator->getQuery()->getResult(),
            'previous' => $firstResult - $resultByPage,
            'current_page' => $currentPage,
            'previous_page' => $firstResult / $resultByPage,
            'next_page' => ($firstResult / $resultByPage) + 2,
            'next' => min(count($paginator), $firstResult + $resultByPage),
            'totalPage' => $totalPage,
        ];
        return $paginatorResult;
    }
}