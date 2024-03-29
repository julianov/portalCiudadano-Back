<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller as BaseController;

use App\Repositories\FormUnitRepository as Repository;
use App\Http\Requests\FormUnits\{
    CreateRequest,
    UpdateByPkRequest,
    DeleteByPkRequest,
    GetListRequest,
    GetListBySearchRequest,
    GetListPublicRequest,
    GetByPkRequest,
    GetElementsByPkRequest,
};
use App\Helpers\FormUnits\{
    GetListFilter,
    GetListBySearchFilter,
    GetListPublicFilter,
    CreateData,
    UpdateData,
};

class FormUnitController extends BaseController
{
    public function __construct(private Repository $repository) {}

    /**
     * Get a list of forms.
     */
    public function getList(GetListRequest $request)
    {
        $data = $request->validated();

        $forms = $this->repository->getList(new GetListFilter($data));

        return response()->json($forms, Response::HTTP_OK);
    }

    public function getPublishedList(GetListPublicRequest $request)
    {
        $data = $request->validated();

        $forms = $this->repository->getPublishedList(new GetListPublicFilter($data));

        return response()->json($forms, Response::HTTP_OK);
    }

    public function getListBySearch(GetListBySearchRequest $request)
    {
        $query = $request->validated();

        $forms = $this->repository->getListBySearch(new GetListBySearchFilter($data));

        return response()->json($forms, Response::HTTP_OK);
    }

    /**
     * Get a form by PK.
     */
    public function getByPk(GetByPkRequest $request)
    {
        $data = $request->validated();

        $form = $this->repository->getByPk($data['code']);

        return response()->json($form, Response::HTTP_OK);
    }

    public function getElementsByPk(GetElementsByPkRequest $request)
    {
        $data = $request->validated();

        $form = $this->repository->getElementsByPk($data['code']);

        return response()->json($form, Response::HTTP_OK);
    }

    /**
     * Create a new form.
     */
    public function create(CreateRequest $request)
    {
        $user = Auth::guard('authentication')->user();

        $data = $request->validated();
        $data['created_by'] = $user->id;

        $form = $this->repository->create(new CreateData($data));

        return response()->json($form, Response::HTTP_CREATED);
    }

    /**
     * Update a form by PK.
     */
    public function updateByPk(UpdateByPkRequest $request)
    {
        $user = Auth::guard('authentication')->user();

        $data = $request->validated();
        $data['updated_by'] = $user->id;

        $form = $this->repository->updateByPk(new UpdateData($data));

        return response()->json($form, Response::HTTP_OK);
    }

    /**
     * Delete a form by PK.
     */
    public function deleteByPk(DeleteByPkRequest $request)
    {
        $data = $request->validated();

        $deleted =$this->repository->removeByPk($data['code']);

        return response()->json($deleted, Response::HTTP_OK);
    }
}
