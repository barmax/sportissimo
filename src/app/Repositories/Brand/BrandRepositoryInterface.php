<?php

namespace App\Repositories\Brand;

use DTO\Brand;
use Nette\Database\Row;

interface BrandRepositoryInterface
{
    public function findByPrimary(int $primary): ?Row;

    public function findAllWithPaginate(): array;
}