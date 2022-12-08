<?php

namespace App\Factories;

use Nette\Utils\Paginator;

class PaginatorFactory
{
    public function create(int $brandsCount, int $pageSize, int $page): Paginator
    {
        $paginator = new Paginator();
        $paginator->setItemCount($brandsCount);
        $paginator->setItemsPerPage($pageSize);
        $paginator->setPage($page);

        return $paginator;
    }
}
