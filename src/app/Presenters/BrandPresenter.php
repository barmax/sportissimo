<?php

namespace App\Presenters;

use App\Factories\PaginatorFactory;
use App\Services\Brand\BrandService;
use App\Enums\SortOrder;
use Nette\Application\UI\Presenter;

final class BrandPresenter extends Presenter
{
    /** @inject */
    public BrandService $service;

    /** @inject */
    public PaginatorFactory $paginatorFactory;

    public function renderAll(
        int $page = 1,
        int $pageSize = 10,
        string $sort = null
    ) {
        $brandsCount = $this->service->getBrandsCount();
        $paginator = $this->paginatorFactory->create(
            $brandsCount,
            $pageSize,
            $page
        );

        $pageCount = ceil($paginator->getItemCount() / $paginator->getItemsPerPage());
        if(isset($sort)) {
            $sort = SortOrder::tryFrom(strtolower($sort));
        }
        $brands = $this->service->getAll($paginator, $sort);

        $this->template->brands = $brands;
        $this->template->pageSizes = [10, 20, 30];
        $this->template->pageCount = (int)$pageCount;
        $this->template->paginator = $paginator;
        $this->template->currentPageSize = $pageSize;
    }

    public function actionCreate(string $name)
    {
        if ($this->isAjax() === false) {
            return $this->sendJson($this->getAjaxErrorResponse());
        }

        return $this->sendJson($this->service->save($name));
    }

    public function actionRead()
    {
        if ($this->isAjax() === false) {
            return $this->sendJson($this->getAjaxErrorResponse());
        }

        $id = $this->getRequest()->post['id'];
        if ($this->isIncorrectId($id)) {
            return $this->sendJson($this->getInvalidIdResponse());
        }

        return $this->sendJson($this->service->read((int)$id));
    }

    public function actionUpdate()
    {
        if ($this->isAjax() === false) {
            return $this->sendJson($this->getAjaxErrorResponse());
        }

        $id = $this->getRequest()->post['id'];
        if ($this->isIncorrectId($id)) {
            return $this->sendJson($this->getInvalidIdResponse());
        }

        $response = $this->service->update(
            (int)$id,
            $this->getRequest()->post['name']
        );

        return $this->sendJson($response);
    }

    public function actionDelete()
    {
        if ($this->isAjax() === false) {
            return $this->sendJson($this->getAjaxErrorResponse());
        }

        $id = $this->getRequest()->post['id'];
        if ($this->isIncorrectId($id)) {
            return $this->sendJson($this->getInvalidIdResponse());
        };

        return $this->sendJson($this->service->delete((int)$id));
    }

    private function getAjaxErrorResponse(): array
    {
        return [
            'status' => 'error',
            'message' => 'The request is not AJAX',
        ];
    }

    private function getInvalidIdResponse(): array
    {
        return [
            'status' => 'error',
            'message' => 'Invalid ID.',
        ];
    }

    private function isIncorrectId(string $id): bool
    {
        return $id == null || is_numeric($id) === false;
    }
}
