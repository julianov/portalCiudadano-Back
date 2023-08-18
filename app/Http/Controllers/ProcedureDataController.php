<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Services\{
    ProcedureDataService as Service,
    ProcedureUnitService,
};
use App\Http\Requests\ProcedureData\{
    CreateRequest,
    UpdateByIdRequest,
    DeleteByIdRequest,
};
use App\Helpers\ProcedureData\{
    CreateData,
    UpdateData,
    DeleteData,
};

class ProcedureDataController extends BaseController
{
    private $service;
    private $procedureUnitService;

    public function __construct(Service $service, ProcedureUnitService $procedureUnitService)
    {
        $this->service = $service;
        $this->procedureUnitService = $procedureUnitService;
    }

    /**
     * Get a list of procedures by user ID.
     */
    public function getList()
    {
        $user = Auth::guard('authentication')->user();

        $procedures = $this->service->getListByUser($user->id);

        return response()->json($procedures, Response::HTTP_OK);
    }

    /**
     * Get a list of public procedures.
     */
    public function getListAvailable()
    {
        $procedures = $this->procedureUnitService->getPublicList();

        return response()->json($procedures, Response::HTTP_OK);
    }

    /**
     * Get a procedure by ID.
     */
    public function getById(int $id)
    {
        $procedure = $this->service->getById($id);

        return response()->json($procedure, Response::HTTP_OK);
    }

    /**
     * Create a new procedure.
     */
    public function create(CreateRequest $request)
    {
        $user = Auth::guard('authentication')->user();

        $data = $request->validated();

        $procedure_unit = $this->procedureUnitService->getById($data['procedure_unit_id']);
        if (!$procedure_unit) return response()->status(Response::HTTP_BAD_REQUEST);

        $data['user_id'] =  $user->id;

        $procedure = $this->service->create(new CreateData($data));

        return response()->json($procedure, Response::HTTP_CREATED);
    }

    /**
     * Update a procedure by ID.
     */
    public function updateById(UpdateByIdRequest $request)
    {
        $data = $request->validated();

        $procedure = $this->service->updateById(new UpdateData($data));

        return response()->json($procedure, Response::HTTP_OK);
    }

    /**
     * Delete a procedure by ID.
     */
    public function deleteById(DeleteByIdRequest $request)
    {
        $data = $request->validated();

        $this->service->removeById(new DeleteData($data));

        return response()->json(null, Response::HTTP_OK);
    }
}
