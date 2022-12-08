<?php

namespace App\EntityManagers\Brand;

use DateTime;
use App\Enums\BrandTable;
use Nette\Database\Explorer;

class BrandEntityManagerExplorer implements BrandEntityManagerInterface
{
    private Explorer $db;

    public function __construct(Explorer $db)
    {
        $this->db = $db;
    }

    /**
     * @inheritDoc
     */
    public function create(string $name): array
    {
        $result = $this->db->table(BrandTable::NAME->value)
            ->insert([
                'name' => $name,
                'created_at' => new DateTime,
            ]);

        return $result->toArray();
    }

    /**
     * @inheritDoc
     */
    public function update(int $primary, string $name): bool
    {
        $result = $this->db->table(BrandTable::NAME->value)
            ->where('id', $primary)
            ->update([
                'name' => $name,
                'updated_at' => new DateTime,
            ]);

        return $result === 1;
    }

    /**
     * @inheritDoc
     */
    public function delete(int $primary): bool
    {
        $result = $this->db->table(BrandTable::NAME->value)
            ->where('id', $primary)
            ->delete();

        return $result === 1;
    }
}
