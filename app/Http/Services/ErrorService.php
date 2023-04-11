<?php


namespace App\Http\Services;
use Illuminate\Http\Response;


class ErrorService{
    public function databaseReadError()
    {
        return response()->json([
            'status' => false,
            'message' => 'Internal server problem, please try again later',
            'error_code' => '001',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function databaseWriteError()
    {
        return response()->json([
            'status' => false,
            'message' => 'Internal server problem, please try again later',
            'error_code' => '002',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function webserverCommunicationError()
    {
        return response()->json([
            'status' => false,
            'message' => 'Internal server problem, please try again later',
            'error_code' => '003',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function dataNotFound()
    {
        return response()->json([
            'status' => false,
            'message' => 'Data inconsistency',
            'error_code' => '004',
        ], Response::HTTP_NOT_FOUND);
    }

    public function badToken()
    {
        return response()->json([
            'status' => false,
            'message' => 'Data inconsistency',
            'error_code' => '005',
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function genericError()
    {
        return response()->json([
            'status' => false,
            'message' => 'Internal server problem',
            'error_code' => '006',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}