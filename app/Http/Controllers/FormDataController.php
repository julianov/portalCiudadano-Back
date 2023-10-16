<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Services\FileStorageService;
use App\Http\Controllers\Controller as BaseController;
use App\Repositories\FormDataRepository as Repository;
use App\Http\Requests\FormData\{
    CreateRequest,
    GetElementsByIdRequest,
    UpdateByIdRequest,
    StoreAttachmentsRequest,
};
use App\Helpers\FormData\{
    CreateData,
    UpdateData,
};

class FormDataController extends BaseController
{
    public function __construct(
        private Repository $repository,
        private FileStorageService $fileStorageService
    ) {}

    public function getList()
    {

        $user = Auth::guard('authentication')->user();

        $forms = $this->repository->getList($user->id);

        return response()->json($forms, Response::HTTP_OK);
    }

    public function getByPk(GetElementsByIdRequest $request)
    {
        $data = $request->validated();
        $user = Auth::guard('authentication')->user();

        $form = $this->repository->getFormByCode($data['form_code'], $user->id);

        return response()->json($form, Response::HTTP_OK);
    }

    /**
     * Create a new form.
     */
    public function create(CreateRequest $request)
    {
        $user = Auth::guard('authentication')->user();

        $data = $request->safe()->except('attachments');
        $data['user_id'] = $user->id;
        
        $formString = $this->repository->create(new CreateData($data));
        
        $attachments = $request->file('attachments');
        
        if ($attachments) {
        $this->fileStorageService->store($attachments, $formString);
        }
        
        $json = json_decode($formString, true);
      
        $formWithAttachments = $this->repository->getFormByCode($json[0]['FORM_UNIT'], $user->id);
       
        return response()->json($formWithAttachments, Response::HTTP_CREATED);
    }


    /**
     * Get a form by PK.
     */
//     public function getByPk(GetByPkRequest $request)
//     {
//         $data = $request->validated();
//
//         $form = $this->repository->getByPk($data['code']);
//
//         return response()->json($form, Response::HTTP_OK);
//     }

    public function getElementsById(GetElementsByIdRequest $request)
    {
        $user = Auth::guard('authentication')->user();

        $data = $request->validated();
        $elements = $this->repository->getElementsById($data['form_code'], $user->id);

        return response()->json($elements, Response::HTTP_OK);
    }

    /**
     * Update a form by PK.
     */
    // TODO: test this
    public function updateById(UpdateByIdRequest $request)
    {
        $user = Auth::guard('authentication')->user();
        $data = $request->safe()->except('attachments');
        $data['user_id'] = $user->id;
        $formString = $this->repository->updateById(new UpdateData($data));

        $attachments = $request->file('attachments');
        
       /* if ($attachments) {
        $this->fileStorageService->store($attachments, $formString);
        }
        */
        $json = json_decode($formString, true);
        $formWithAttachments = $this->repository->getFormByCode($json[0]['FORM_UNIT'], $user->id);
        $elements = $this->repository->getElementsById($json[0]['FORM_UNIT'], $user->id);
        
 // Decodificar el JSON en un arreglo asociativo
$formWithAttachmentsArray = json_decode($formWithAttachments, true);

// Agregar el nuevo campo al objeto
$nuevoCampo = $elements;
foreach ($formWithAttachmentsArray as &$obj) {
    $obj["ELEMENTS"] = $nuevoCampo;
}

// Codificar el arreglo asociativo de vuelta a JSON
$nuevoJsonString = json_encode($formWithAttachmentsArray);


        return response()->json($nuevoJsonString, Response::HTTP_OK);
    }

    /**
     * Delete a form by PK.
     */
//     public function deleteByPk(DeleteByPkRequest $request)
//     {
//         $deleted =$this->repository->removeByPk($request['code']);
//
//         return response()->json($deleted, Response::HTTP_OK);
//     }

    /**
     * Store attachments.
     */
    public function storeAttachments(StoreAttachmentsRequest $request)
    {
        $user = Auth::guard('authentication')->user();

        $validated = $request->validated();
        
        $procedure = $this->repository->getFormByCode($validated['procedure_data_id'], $user->id);

        $attachments = $this->repository->storeAttachments($validated['attachments'], $procedure);

        return response()->json($attachments, Response::HTTP_OK);
    }

    // TODO: test this
    public function getAttachmentById(int $attachmentId)
    {
        $attachment = $this->repository->getUploadedFile($attachmentId);

        return response()->json($attachment, Response::HTTP_OK);
    }

    // TODO: test this
    public function deleteAttachmentById(int $attachmentId)
    {
        $this->repository->deleteUploadedFile($attachmentId);

        return response()->json(null, Response::HTTP_OK);
    }
}
