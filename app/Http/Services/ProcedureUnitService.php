<?php

namespace App\Http\Services;

use App\Repositories\ProcedureUnitRepository as Repository;

use App\Helpers\ProcedureUnits\{
    CreateData,
    UpdateData,
    SearchFilter,
};

class ProcedureUnitService
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get a list of procedures.
     */
    public function getList()
    {
        $procedures = $this->repository->getList();

        return $procedures;
    }

    public function getListBySearch(SearchFilter $filter)
    {
        $procedures = $this->repository->getListBySearch($filter);

        return $procedures;
    }

    /**
     * Get a list of categories.
     */
    public function getCategories()
    {
        $categories = $this->repository->getCategories();

        return $categories;
    }

    /**
     * Get a procedure by PK.
     */
    public function getByPk(string $id)
    {
        $procedure = $this->repository->getByPk($id);

        return $procedure;
    }

    /**
     * Create a new procedure.
     */
    public function create(CreateData $data)
    {
        $procedure = $this->repository->create($data);

        return $procedure;
    }

    /**
     * Update a procedure by PK.
     */
    public function updateByTitle(UpdateData $data)
    {
        $procedure = $this->repository->updateByTitle($data);

        return $procedure;
    }

    /**
     * Remove a procedure by PK.
     */
    public function removeByTitle(string $title)
    {
        $result = $this->repository->removeByTitle($title);

        return $result;
    }

    public function removeById(int $id)
    {
        $result = $this->repository->removeById($id);

        return $result;
    }
}
