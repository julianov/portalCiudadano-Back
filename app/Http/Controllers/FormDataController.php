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
        
        $formWithAttachments = $this->repository->getById($json[0]['ID']);
       
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
        $validated = $request->validated();

        $form = $this->repository->updateById(new UpdateData($validated));

        $attachments = $request->file('attachments');
        if ($attachments) {
            $this->fileStorageService->store($attachments, $form);
        }

        return response()->json($form, Response::HTTP_OK);
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
        $validated = $request->validated();

        $procedure = $this->repository->getById($validated['procedure_data_id']);

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
