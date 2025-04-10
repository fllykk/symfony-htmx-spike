<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as OrmPaginator;

class Paginator
{
    // define constatnte limite
    const defaultLimit = 10;
    const maxLimit = 100;

    public static function paginate(QueryBuilder $queryBuilder, int $page = 1, ?int $limit = null): array
    {
        $page = $page <= 0 ? 1 : $page;

        $limit = $limit ?? self::defaultLimit;
        $limit = min($limit, self::maxLimit);
        $offset = ($page - 1) * $limit;

        return self::getResultsPaginated($queryBuilder, $page, $limit);

    }

    public static function getResultsPaginated(QueryBuilder $queryBuilder, $currentPage, $limit): array
    {
        $offset = ($currentPage - 1) * $limit;

        $queryBuilder->setMaxResults($limit)
            ->setFirstResult($offset);

        $paginator = new OrmPaginator($queryBuilder);
        $totalResults = $paginator->count();
        $totalPages = (int) ceil($totalResults / $limit);

        if ($currentPage > $totalPages){
            $currentPage = $totalPages;
            return self::getResultsPaginated($queryBuilder, $currentPage, $limit);
        }

        return [
            "items" => iterator_to_array($paginator),
            "currentPage" => $currentPage,
            "limit" => $limit,
            "total" => $totalResults,
            "totalPages" => $totalPages
        ];
    }


}