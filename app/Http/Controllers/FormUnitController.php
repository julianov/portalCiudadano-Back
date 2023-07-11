<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller as BaseController;

use App\Http\Services\FormUnitService;
use App\Http\Requests\FormUnit\{
    CreateRequest,
    UpdateByPkRequest,
};
use App\Helpers\{
    FormUnitCreateData as CreateData,
    FormUnitUpdateData as UpdateData,
};

class FormUnitController extends BaseController
{
    private $service;

    public function __construct(FormUnitService $service)
    {
        $this->service = $service;
    }

    /**
     * Get a list of forms.
     */
    public function getList()
    {
        $forms = $this->service->getList();

        return response()->json($forms, Response::HTTP_OK);
    }

    /**
     * Get a form by PK.
     */
    public function getByPk(string $code, int $version)
    {
        $form = $this->service->getByPk($code, $version);

        return response()->json($form, Response::HTTP_OK);
    }

    /**
     * Create a new form.
     */
    public function create(CreateRequest $request)
    {
      //  $user = Auth::guard('authentication')->user();

        $data = $request->validated();
        //$data['created_by'] = $user->id;
        $data['created_by'] = 48;

        $form = $this->service->create(new CreateData($data));

        return response()->json($form, Response::HTTP_CREATED);
    }

    /**
     * Update a form by PK.
     */
    public function updateByPk(UpdateByPkRequest $request, string $code, int $version)
    {
        $userId = $request->user()->id;

        $data = $request->validated();
        if ($data['fields']) {
            return response()->json(['message' => 'Fields cannot be updated'], Response::HTTP_BAD_REQUEST);
        }
        $data['updatedBy'] = $userId;

        $form = $this->service->updateByPk($code, $version, new UpdateData($data));

        return response()->json($form, Response::HTTP_OK);
    }

    /**
     * Delete a form by PK.
     */
    public function deleteByPk(string $code, int $version)
    {
        $this->service->removeByPk($code, $version);

        return response()->json(true, Response::HTTP_OK);
    }
}
