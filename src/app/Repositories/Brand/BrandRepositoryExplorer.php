<?php

namespace App\Repositories\Brand;

use App\Enums\SortOrder;
use App\Enums\BrandTable;
use Nette\Database\Explorer;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\Paginator;

class BrandRepositoryExplorer implements BrandRepositoryInterface
{
    private Explorer $db;

    public function __construct(Explorer $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritDoc
     */
    public function findByPrimary(int $primary): ?ActiveRow
    {
        return $this->db
            ->table(BrandTable::NAME->value)
            ->where('id', $primary)
            ->fetch();
    }

    /**
     * @inheritDoc
     */
    public function findAll(
        Paginator $paginator,
        SortOrder $sort = null
    ): array {
        $query = $this->db
            ->table(BrandTable::NAME->value)
            ->page(
                $paginator->getPage(),
                $paginator->getItemsPerPage()
            );

        $orderBy = match ($sort) {
            SortOrder::DESC => 'name DESC',
            SortOrder::ASC => 'name ASC',
            default => 'id DESC'
        };

        return $query
            ->order($orderBy)
            ->fetchAll();
    }

    /**
     * @inheritDoc
     */
    public function isUniqueName(string $name): bool
    {
        $result = $this->db->table(BrandTable::NAME->value)
            ->where('name', $name)
            ->count('id');

        if ($result > 0) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getBrandsCount(): int
    {
        return $this->db->table(BrandTable::NAME->value)
            ->count('id');
    }
}
