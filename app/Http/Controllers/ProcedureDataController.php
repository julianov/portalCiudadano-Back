<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Services\ProcedureUnitService;
use App\Repositories\ProcedureDataRepository as Repository;
use App\Http\Requests\ProcedureData\{
    CreateRequest,
    UpdateByIdRequest,
    DeleteByIdRequest,
    StoreAttachmentsRequest,
};
use App\Helpers\ProcedureData\{
    CreateData,
    UpdateData,
    DeleteData,
};

class ProcedureDataController extends BaseController
{
    public function __construct(
        private ProcedureUnitService $procedureUnitService,
        private Repository $repository
    ) {}

    /**
     * Get a list of procedures by user ID.
     */
    public function getList()
    {
        $user = Auth::guard('authentication')->user();

        $procedures = $this->repository->getListByUser($user->id);

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

        $procedure_unit = $this->procedureUnitService->getById($data['procedure_unit_id']);
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
