<?php

namespace App\Services\Brand;

use App\EntityManagers\Brand\BrandEntityManagerInterface;
use App\Repositories\Brand\BrandRepositoryInterface;
use App\Validators\Brand\BrandValidator;
use App\Enums\SortOrder;
use Exception;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\Paginator;

class BrandService
{
    private BrandRepositoryInterface $repository;

    private BrandEntityManagerInterface $entityManager;

    private BrandValidator $validator;

    public function __construct(
        BrandRepositoryInterface $repository,
        BrandEntityManagerInterface $entityManager,
        BrandValidator $validator
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function save(string $name): array
    {
        try {
            $this->validator->validate($name);
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage());
        }

        $brand = $this->entityManager->create($name);
        if (empty($brand)) {
            return $this->getErrorResponse();
        }

        return [
            'status' => 'success'
        ];
    }

    public function update(int $id, string $name): array
    {
        try {
            $this->validator->validate($name);
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage());
        }

        $result = $this->entityManager->update($id, $name);

        return $this->proceedToResponse($result);
    }

    public function delete(int $id): array
    {
        $result = $this->entityManager->delete($id);

        return $this->proceedToResponse($result);
    }

    public function getAll(Paginator $paginator, SortOrder $sort = null): array
    {
        return $this->repository->findAll($paginator, $sort);
    }

    public function read(int $id): array
    {
        $brand = $this->repository->findByPrimary($id);

        return [
            'status' => 'success',
            'data' => $brand->toArray()
        ];
    }

    public function getBrandsCount(): int
    {
        return $this->repository->getBrandsCount();
    }

    private function proceedToResponse(bool $result): array
    {
        if ($result === false) {
            return $this->getErrorResponse('Invalid ID');
        }

        return [
            'status' => 'success',
        ];
    }

    private function getErrorResponse(string $message = null): array
    {
        $response = [
            'status' => 'error',
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        return $response;
    }
}
