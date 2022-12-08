<?php

namespace App\EntityManagers\Brand;

interface BrandEntityManagerInterface
{
    /**
     * Inserts new record about a brand.
     *
     * @param string $name
     *
     * @return array
     */
    public function create(string $name): array;

    /**
     * Updates the brand record.
     *
     * @param int    $primary
     * @param string $name
     *
     * @return bool
     */
    public function update(int $primary, string $name): bool;

    /**
     * Deletes the brand.
     *
     * @param int $primary
     *
     * @return bool
     */
    public function delete(int $primary): bool;
}
