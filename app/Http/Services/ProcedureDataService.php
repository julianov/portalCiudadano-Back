<?php

namespace App\Http\Services;

use App\Repositories\ProcedureDataRepository as Repository;

use App\Helpers\ProcedureData\{
    CreateData,
    UpdateData,
    DeleteData,
};

class ProcedureDataService
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get a list of procedures.
     */
    public function getListByUser(int $user)
    {
        $procedures = $this->repository->getListByUser($user);

        return $procedures;
    }

    /**
     * Get a procedure by ID.
     */
    public function getById(int $id)
    {
        $procedure = $this->repository->getById($id);

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
    public function updateById(UpdateData $data)
    {
        $procedure = $this->repository->updateById($data);

        return $procedure;
    }

    /**
     * Remove a procedure by PK.
     */
    public function removeById(DeleteData $data)
    {
        $result = $this->repository->removeById($data);

        return $result;
    }
}
