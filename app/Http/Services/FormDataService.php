<?php

namespace App\Http\Services;

use App\Helpers\FormData\{
    CreateData,
    UpdateData,
};
use App\Repositories\FormDataRepository as Repository;

class FormDataService
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get a list of forms.
     */
//     public function getList()
//     {
//         $forms = $this->repository->getList();
//
//         return $forms;
//     }

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
//     public function getById(int $id)
//     {
//         $form = $this->repository->getById($id);
//
//         return $form;
//     }

    /**
     * Update a form by PK.
     */
//     public function updateById(UpdateData $data)
//     {
//         $form = $this->repository->updateById($data);
//
//         return $form;
//     }

    /**
     * Remove a form by PK.
     */
//     public function removeById(int $id)
//     {
//         $result = $this->repository->removeById($id);
//
//         return $result;
//     }
}
