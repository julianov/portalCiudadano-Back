<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller as BaseController;

use App\Http\Services\FormUnitService;
use App\Http\Requests\FormUnits\{
    CreateRequest,
    UpdateByPkRequest,
};
use App\Helpers\FormUnits\{
    CreateData,
    UpdateData,
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
     * Get a form by PK.
     */
    public function getByPk(string $code)
    {
        $form = $this->service->getByPk($code);

        return response()->json($form, Response::HTTP_OK);
    }

    /**
     * Update a form by PK.
     */
    public function updateByPk(UpdateByPkRequest $request, string $code)
    {
        //  $user = Auth::guard('authentication')->user();

        $data = $request->validated();
        $data['updated_by'] = 48;
        // $data['updated_by'] = $user->id;

        $form = $this->service->updateByPk($code, new UpdateData($data));

        return response()->json($form, Response::HTTP_OK);
    }

    /**
     * Delete a form by PK.
     */
    public function deleteByPk(string $code)
    {
        $this->service->removeByPk($code);

        return response()->json(true, Response::HTTP_OK);
    }
}
