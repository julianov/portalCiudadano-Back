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
    GetFormAttachment,
    DeleteAttachmentsRequest,
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
        $decodedelements = json_decode(stripslashes($elements), true);

        foreach ($formWithAttachmentsArray as &$obj) {
            $obj["ELEMENTS"] = $decodedelements;
        }

        // Codificar el arreglo asociativo de vuelta a JSON
        $nuevoJsonString = json_encode($formWithAttachmentsArray);

        return response()->json($nuevoJsonString, Response::HTTP_OK);
    }

    /**
     * Store attachments.
     */
    public function storeAttachments(StoreAttachmentsRequest $request)
    {
        $user = Auth::guard('authentication')->user();

        $validated = $request->validated();
        
        $form_data = $this->repository->getById($request['form_data_id']);

        $attachments = $this->repository->storeAttachments($validated['attachments'], $form_data);

        return response()->json($attachments, Response::HTTP_OK);
    }

    // TODO: test this
    public function getAttachmentById(GetFormAttachment $request)
    {
        $validated = $request->validated();

        $attachment = $this->repository->getUploadedFile('NOTIFICATIONS_DOC', $validated['attachmentId']);
        
        return $attachment;
    }

    public function getAttachmentName (GetFormAttachment $request)
    {
        $validated = $request->validated();

        $attachment_name = $this->repository->getAttachmentFileName('NOTIFICATIONS_DOC', $validated['attachmentId']);
        
        if ($attachment_name){

            return response()->json([
                'status' => true,
                'attachment_name' => $attachment_name
            ], 200);

        }else{

            return $this->errorService->databaseReadError();

        }
    }

    // TODO: test this
    public function deleteAttachmentById(DeleteAttachmentsRequest $request)
    {
        $validated = $request->validated();

        $form_data = $this->repository->getById($request['form_data_id']);

        $data = json_decode($form_data);

        // Obtener los valores de MULTIMEDIA_ID y ATTACHMENTS
        if (!empty($data)) {
            $multimediaId = $data[0]->MULTIMEDIA_ID;
            $attachments = $data[0]->ATTACHMENTS;
            if (strpos($multimediaId, ',') !== false) {
                $multimediaIdsArray = explode(',', $multimediaId);
                $attachmentsArray = explode(',', $attachments);
                $position = array_search($request['multimedia_id'], $multimediaIdsArray);
                if ($position !== false){
                    unset($multimediaIdsArray[$position]);
                    // Reindexar el array para que las claves sean numéricas consecutivas
                    $multimediaIdsArray = array_values($multimediaIdsArray);
                    // Generar la nueva cadena separada por comas
                    $newMultimediaIdString=null;
                    if (count($multimediaIdsArray) > 1) {
                        $newMultimediaIdString = implode(',', $multimediaIdsArray);
                    } else {
                        // Si solo hay un elemento en $multimediaIdsArray, asignarlo directamente a $newMultimediaIdString
                        $newMultimediaIdString = reset($multimediaIdsArray); // Obtiene el primer elemento del arreglo
                    }

                    unset($attachmentsArray[$position]);
                    // Reindexar el array para que las claves sean numéricas consecutivas
                    $attachmentsArray = array_values($attachmentsArray);

                    $newAttachmentsString=null;
                    if (count($attachmentsArray) > 1) {
                        $newAttachmentsString = implode(',', $attachmentsArray);
                    } else {
                        // Si solo hay un elemento en $multimediaIdsArray, asignarlo directamente a $newMultimediaIdString
                        $newAttachmentsString = reset($attachmentsArray); // Obtiene el primer elemento del arreglo
                    }

                    $this->repository->deleteMultimedia($newAttachmentsString ,$newMultimediaIdString, $request['form_data_id'] );
                    $this->repository->deleteUploadedFile($validated['multimedia_id']);

                    return response()->json($validated['multimedia_id'], Response::HTTP_OK);

                }else{
                    return response()->json(['error' => 'Problemas en la base de datos: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);

                }
            } else {
                $this->repository->deleteMultimedia("" ,"", intval($request['form_data_id']) );
                $this->repository->deleteUploadedFile($validated['multimedia_id']);
                return response()->json($validated['multimedia_id'], Response::HTTP_OK);

            }

        } else {
            return response()->json(['error' => 'Problemas en la base de datos: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
