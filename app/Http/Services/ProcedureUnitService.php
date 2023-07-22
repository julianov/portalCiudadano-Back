<?php

namespace App\Http\Services;

use App\Repositories\ProcedureUnitRepository as Repository;

use App\Helpers\ProcedureUnits\{
    CreateData,
    UpdateData,
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
    public function updateByPk(UpdateData $data)
    {
        $procedure = $this->repository->updateByPk($data);

        return $procedure;
    }

    /**
     * Remove a procedure by PK.
     */
    public function removeByPk(string $code)
    {
        $result = $this->repository->removeByPk($code);

        return $result;
    }
}
