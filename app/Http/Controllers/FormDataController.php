<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Services\FileStorageService;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Services\FormDataService as Service;
use App\Http\Requests\FormData\{
    CreateRequest,
    UpdateByPkRequest,
    DeleteByPkRequest,
    GetByPkRequest
};
use App\Helpers\FormData\{
    CreateData,
    UpdateData,
};

class FormDataController extends BaseController
{
    private $service;
    private $fileStorageService;

    public function __construct(Service $service, FileStorageService $fileStorageService)
    {
        $this->service = $service;
        $this->fileStorageService = $fileStorageService;
    }

    /**
     * Create a new form.
     */
    public function create(CreateRequest $request)
    {
        $user = Auth::guard('authentication')->user();

        $data = $request->safe()->except('attachments');
        $data['user_id'] = $user->id;

        $form = $this->service->create(new CreateData($data));

        $attachments = $request->file('attachments');
        if ($attachments) {
            $this->fileStorageService->store($attachments, $form);
        }

        return response()->json($form, Response::HTTP_CREATED);
    }


    /**
     * Get a form by PK.
     */
//     public function getByPk(GetByPkRequest $request)
//     {
//         $data = $request->validated();
//
//         $form = $this->service->getByPk($data['code']);
//
//         return response()->json($form, Response::HTTP_OK);
//     }

    /**
     * Update a form by PK.
     */
//     public function updateByPk(UpdateByPkRequest $request)
//     {
//         $user = Auth::guard('authentication')->user();
//
//         $data = $request->validated();
//         //$data['updated_by'] = 48;
//         $data['updated_by'] = $user->id;
//
//         $form = $this->service->updateByPk($request['code'], new UpdateData($data));
//
//         return response()->json($form, Response::HTTP_OK);
//     }

    /**
     * Delete a form by PK.
     */
//     public function deleteByPk(DeleteByPkRequest $request)
//     {
//         $deleted =$this->service->removeByPk($request['code']);
//
//         return response()->json($deleted, Response::HTTP_OK);
//     }
}
