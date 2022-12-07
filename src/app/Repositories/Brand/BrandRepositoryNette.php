<?php

namespace App\Repositories\Brand;

use DTO\Brand;
use Nette\Database\Connection;
use Nette\Database\Row;

class BrandRepositoryNette implements BrandRepositoryInterface
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function findByPrimary(int $primary): ?Row
    {
        return $this->db
            ->query('SELECT id, name, created_at, updated_at FROM brands WHERE id =: primary')
            ->fetch();
    }

    public function findAllWithPaginate(): array
    {
        return $this->db
            ->query('SELECT id, name, created_at, updated_at FROM brands')
            ->fetchAll();
    }
}