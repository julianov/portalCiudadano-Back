<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

use App\Repositories\FileStorageRepository as Repository;

class FileStorageService
{
    private $repository;

    public function __construct(Repository $repository) {
        $this->repository = $repository;
    }

    /**
     * @param $files Files to be stored
     * @param $row Inserted row (as plain JSON.stringify())
     */
    public function store(UploadedFile|array $files, string $row)
    {
        $ids = [];

        $dataArray = json_decode($row, true);
        $id = $dataArray[0]['ID'];

        foreach ($files as $file) {
            $id = $this->storeSingleFile($file, $id);
            array_push($ids, $id);
        }

        return $ids;
    }

    private function storeSingleFile(UploadedFile $file, int $id)
    {
        $file_id = $this->repository->store($file, $id);

        return $file_id;
    }
}
