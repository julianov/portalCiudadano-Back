<?php

namespace App\Http\Services;

use App\Repositories\ProcedureDataRepository as Repository;

use App\Helpers\ProcedureData\{
    CreateData,
    UpdateData,
    DeleteData,
};
use Illuminate\Http\UploadedFile;

use PDO;
use Illuminate\Support\Facades\DB;

class ProcedureDataService
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get a list of procedures.
     */
    public function getListByUser(int $user)
    {
        $procedures = $this->repository->getListByUser($user);

        return $procedures;
    }

    /**
     * Get a procedure by ID.
     */
    public function getById(int $id)
    {
        $procedure = $this->repository->getById($id);

        return $procedure;
    }

    /**
     * Create a new procedure.
     */
    public function create(CreateData $data)
    {
        $procedure = $this->repository->create($data);

        return $procedure;
    }

    /**
     * Update a procedure by PK.
     */
    public function updateById(UpdateData $data)
    {
        $procedure = $this->repository->updateById($data);

        return $procedure;
    }

    /**
     * Remove a procedure by PK.
     */
    public function removeById(DeleteData $data)
    {
        $result = $this->repository->removeById($data);

        return $result;
    }

    public function storeAttachments(UploadedFile|array $files, string $row)
    {
        $ids = [];

        $dataArray = json_decode($row, true);
        $procedureId = $dataArray[0]['ID'];

        foreach ($files as $file) {
            $id = $this->storeSingleFile($file, $procedureId);
            array_push($ids, $id);
        }

        return $ids;

    }

    private function storeSingleFile(UploadedFile $file, int $procedureId)
    {
        function getFileType($file) {
            $image_extensions = ['png', 'jpg', 'jpeg'];
            $file_extension = $file->getClientOriginalExtension();
            $is_image = in_array(strtolower($file_extension), $image_extensions);
            return $is_image ? 'IMG' : 'DOC';
        }

        $pkg = "CIUD_TRAMITES_DATA_PKG";
        $pointer = null;
		$blob_file =file_get_contents($file);

        $query = "{$pkg}.PROCEDURE_DATA_ADJUNTO";
        $bindings = [
            'p_file' => [
                "value" => &$blob_file,
                "type" => PDO::PARAM_LOB,
                "size" => $file->getSize()
            ],
            'file_type' => getFileType($file),
            'file_extension' => $file->getClientOriginalExtension(),
            'procedure_data_table_id' => intval($procedureId),
            'file_name' => $file->getClientOriginalName(),
            'P_multimedia_id' => [
                'value' => &$pointer,
                'type' => PDO::PARAM_INT
            ]
        ];

        DB::executeProcedure($query, $bindings);

        return $pointer;
    }
}
