<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Services\ProcedureUnitService;
use App\Repositories\ProcedureDataRepository as Repository;
use App\Http\Requests\ProcedureData\{
    GetListPublicRequest,
    GetListBySearchRequest,
    CreateRequest,
    UpdateByIdRequest,
    DeleteByIdRequest,
    StoreAttachmentsRequest,
    DeleteAttachmentsRequest,
    GetProcedureAttachment
};
use App\Helpers\ProcedureData\{
    GetListBySearchFilter,
    CreateData,
    UpdateData,
    DeleteData,
};
use App\Repositories\ProcedureUnitRepository;
use App\Helpers\ProcedureUnits\{
    GetListPublicFilter,
};
use App\Helpers\ProcedureData\{
    GetListFilter,
};

class ProcedureDataController extends BaseController
{
    public function __construct(
        private ProcedureUnitRepository $procedureUnitRepository,
        private Repository $repository
    ) {}

    /**
     * Get a list of procedures by user ID.
     */
    public function getList(GetListPublicRequest $request)
    {
        $user = Auth::guard('authentication')->user();
        $data = $request->validated();

        $procedures = $this->repository->getListByUser(new GetListFilter($data), $user->id);

        return response()->json($procedures, Response::HTTP_OK);
    }

    /**
     * Get a list of public procedures.
     */
    public function getListAvailable(GetListPublicRequest $request)
    {
        $data = $request->validated();

        $procedures = $this->procedureUnitRepository->getPublicList(new GetListPublicFilter($data));

        return response()->json($procedures, Response::HTTP_OK);
    }

    public function getListBySearch(GetListBySearchRequest $request)
    {
        $data = $request->validated();
        
        $procedures = $this->procedureUnitRepository->getListBySearch(new GetListBySearchFilter($data));

        return response()->json($procedures, Response::HTTP_OK);
    }
    /**
     * Get a procedure by ID.
     */
    public function getById(int $id)
    {
        $procedure = $this->repository->getById($id);

        return response()->json($procedure, Response::HTTP_OK);
    }

      /**
     * Get a procedure by ID.
     */
    public function getByProcedureUnitId(int $id)
    {
        $procedure = $this->repository->getByProcedureUnitId($id);

        return response()->json($procedure, Response::HTTP_OK);
    }

    /**
     * Create a new procedure.
     */
    public function create(CreateRequest $request)
    {
        $user = Auth::guard('authentication')->user();

        $data = $request->validated();

        $procedure_unit = $this->procedureUnitRepository->getById($data['procedure_unit_id']);
        if (!$procedure_unit) return response()->status(Response::HTTP_BAD_REQUEST);

        $data['user_id'] =  $user->id;

        $procedure = $this->repository->create(new CreateData($data));

        return response()->json($procedure, Response::HTTP_CREATED);
    }

    /**
     * Update a procedure by ID.
     */
    public function updateById(UpdateByIdRequest $request)
    {
        $data = $request->validated();

        $procedure = $this->repository->updateById(new UpdateData($data));

        return response()->json($procedure, Response::HTTP_OK);
    }

    /**
     * Delete a procedure by ID.
     */
    public function deleteById(DeleteByIdRequest $request)
    {
        $data = $request->validated();

        $this->repository->removeById(new DeleteData($data));

        return response()->json(null, Response::HTTP_OK);
    }

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
    public function getAttachmentById(GetProcedureAttachment $request)
    {
        $validated = $request->validated();

        $attachment = $this->repository->getUploadedFile('NOTIFICATIONS_DOC', $validated['attachmentId']);
        
        return $attachment;
    }

    public function getAttachmentName (GetProcedureAttachment $request)
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

        $procedure_data = $this->repository->getById($request['procedure_data_id']);

        $data = json_decode($procedure_data);

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

                    $this->repository->deleteMultimedia($newAttachmentsString ,$newMultimediaIdString, $request['procedure_data_id'] );
                    $this->repository->deleteUploadedFile($validated['multimedia_id']);

                    return response()->json($validated['multimedia_id'], Response::HTTP_OK);

                }else{
                    return response()->json(['error' => 'Problemas en la base de datos: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);

                }
            } else {
                $this->repository->deleteMultimedia("" ,"", intval($request['procedure_data_id']) );
                $this->repository->deleteUploadedFile($validated['multimedia_id']);
                return response()->json($validated['multimedia_id'], Response::HTTP_OK);

            }

        } else {
            return response()->json(['error' => 'Problemas en la base de datos: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
