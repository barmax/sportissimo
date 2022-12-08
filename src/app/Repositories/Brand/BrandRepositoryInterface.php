<?php

namespace App\Repositories\Brand;

use App\Enums\SortOrder;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\Paginator;

interface BrandRepositoryInterface
{
    /**
     * Finds a brand by a primary key.
     *
     * @param int $primary
     *
     * @return ActiveRow|null
     */
    public function findByPrimary(int $primary): ?ActiveRow;

    /**
     * Finds all brands by a paginator and a sort destination.
     *
     * @param Paginator $paginator
     * @param SortOrder $sort
     *
     * @return array
     */
    public function findAll(Paginator $paginator, SortOrder $sort): array;

    /**
     * Checks is unique name of the brand
     *
     * @param string $name
     *
     * @return bool
     */
    public function isUniqueName(string $name): bool;

    /**
     * @return int
     */
    public function getBrandsCount(): int;
}
