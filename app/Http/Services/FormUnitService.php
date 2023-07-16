<?php

namespace App\Http\Services;

use App\Helpers\FormUnits\{
    CreateData,
    UpdateData,
};
use App\Repositories\FormUnitRepository as Repository;

class FormUnitService
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get a list of forms.
     */
    public function getList()
    {
        $forms = $this->repository->getList();

        return $forms;
    }

    /**
     * Create a new form.
     */
    public function create(CreateData $data)
    {
        $form = $this->repository->create($data);

        return $form;
    }

    /**
     * Get a form by PK.
     */
    public function getByPk(string $code)
    {
        $form = $this->repository->getByPk($code);

        return $form;
    }

    /**
     * Update a form by PK.
     */
    public function updateByPk(string $code, UpdateData $data)
    {
        $form = $this->repository->updateByPk($code, $data);

        return $form;
    }

    /**
     * Remove a form by PK.
     */
    public function removeByPk(string $code)
    {
        $result = $this->repository->removeByPk($code);

        return $result;
    }
}
