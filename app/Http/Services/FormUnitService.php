<?php

namespace App\Http\Services;

use App\Helpers\{
    FormUnitCreateData as CreateData,
    FormUnitUpdateData as UpdateData,
};
use App\Repositories\FormUnitRepository as Repository;
use App\Models\FormUnitModel as Model;

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
    public function getList(): array
    {
        $forms = $this->repository->getList();

        return $forms;
    }

    /**
     * Get a form by PK.
     */
    public function getByPk(string $code, int $version): Model
    {
        $form = $this->repository->getByPk($code, $version);

        return $form;
    }

    /**
     * Create a new form.
     */
    public function create(CreateData $data): Model
    {
        // $code = $data->get('code');
        // $lastVersionRegister = $this->repository->getLastVersionRegisterByCode($code);
        // if ($lastVersionRegister) {
        //     $data->setVersion($lastVersionRegister->get('version') + 1);
        // }
        $form = $this->repository->create($data);

        return $form;
    }

    /**
     * Update a form by PK.
     */
    public function updateByPk(string $code, int $version, UpdateData $data): Model
    {
        $form = $this->repository->updateByPk($code, $version, $data);

        return $form;
    }

    //     /**
    //      * Delete a form by ID.
    //      */
    //     public function deleteById(int $id, int $userId)
    //     {
    //         $result = $this->repository->deleteById($id, $userId);
    //
    //         return $result;
    //     }

    /**
     * Remove a form by PK.
     */
    public function removeByPk(string $code, int $version): bool
    {
        $result = $this->repository->removeByPk($code, $version);

        return $result;
    }
}
