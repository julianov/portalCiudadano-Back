<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

use App\Repositories\ProcedureUnitRepository as Repository;
use App\Http\Requests\ProcedureUnits\{
    // GetListRequest,
    GetListBySearchRequest,
    CreateRequest,
    // GetByIdRequest,
    UpdateByPkRequest,
    DeleteByPkRequest,
};
use App\Helpers\ProcedureUnits\{
    CreateData,
    UpdateData,
    SearchFilter,
};

class ProcedureUnitController extends BaseController
{
    public function __construct(private Repository $repository) {}

    /**
     * Get a list of procedures.
     */
    public function getList()
    {
        $procedures = $this->repository->getList();

        return response()->json($procedures, Response::HTTP_OK);
    }

    /**
     * Get a list of procedures by search.
     */
    public function getListBySearch(GetListBySearchRequest $request)
    {
        $query = $request->query();

        $procedures = $this->repository->getListBySearch(new SearchFilter($query));

        return response()->json($procedures, Response::HTTP_OK);
    }

    /**
     * Get a list of procedure categories.
     */
    public function getCategories()
    {
        $categories = $this->repository->getCategories();

        return response()->json($categories, Response::HTTP_OK);
    }

    /**
     * Get a procedure by ID.
     */
    public function getById(string $id)
    {
        $procedure = $this->repository->getById($id);

        return response()->json($procedure, Response::HTTP_OK);
    }

    /**
     * Create a new procedure.
     */
    public function create(CreateRequest $request)
    {
        $user = Auth::guard('authentication')->user();

        $data = $request->validated();
        $data['created_by'] =  $user->id;

        $procedure = $this->repository->create(new CreateData($data));

        return response()->json($procedure, Response::HTTP_CREATED);
    }

    /**
     * Update a procedure by ID.
     */
    public function updateByTitle(UpdateByPkRequest $request)
    {
        $user = Auth::guard('authentication')->user();

        $data = $request->validated();
        $data['updated_by'] =  $user->id;

        $procedure = $this->repository->updateByTitle(new UpdateData($data));

        return response()->json($procedure, Response::HTTP_OK);
    }

    /**
     * Delete a procedure by ID.
     */
    public function deleteById(DeleteByPkRequest $request)
    {
        $data = $request->validated();

        $this->repository->removeById($data['id']);

        return response()->json(null, Response::HTTP_OK);
    }
}
