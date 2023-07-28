<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

use App\Http\Controllers\Controller as BaseController;

use App\Http\Services\ProcedureUnitService as Service;
use App\Http\Requests\ProcedureUnits\{
    // GetListRequest,
    CreateRequest,
    // GetByIdRequest,
    UpdateByPkRequest,
    DeleteByPkRequest,
};
use App\Helpers\ProcedureUnits\{
    CreateData,
    UpdateData,
};

class ProcedureUnitController extends BaseController
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Get a list of procedures.
     */
    public function getList()
    {
        $procedures = $this->service->getList();

        return response()->json($procedures, Response::HTTP_OK);
    }

    /**
     * Get a list of procedure categories.
     */
    public function getCategories()
    {
        $categories = $this->service->getCategories();

        return response()->json($categories, Response::HTTP_OK);
    }

    /**
     * Get a procedure by ID.
     */
    public function getByPk(string $id)
    {
        $procedure = $this->service->getByPk($id);

        return response()->json($procedure, Response::HTTP_OK);
    }

    /**
     * Create a new procedure.
     */
    public function create(CreateRequest $request)
    {
        // $userId = $request->user()->id;

        $data = $request->validated();
        // $data->createdBy = $userId;
        $data['created_by'] = 48;

        $procedure = $this->service->create(new CreateData($data));

        return response()->json($procedure, Response::HTTP_CREATED);
    }

    /**
     * Update a procedure by ID.
     */
    public function updateByTitle(UpdateByPkRequest $request)
    {
        // $userId = $request->user()->id;

        $data = $request->validated();
        // $data->updatedBy = $userId;
        $data['updated_by'] = 48;

        $procedure = $this->service->updateByTitle(new UpdateData($data));

        return response()->json($procedure, Response::HTTP_OK);
    }

    /**
     * Delete a procedure by ID.
     */
    public function deleteByTitle(DeleteByPkRequest $request)
    {
        $data = $request->validated();

        $this->service->removeByTitle($data['title']);

        return response()->json(null, Response::HTTP_OK);
    }
}
