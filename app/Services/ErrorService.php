<?php


namespace App\Services;
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

    public function dataInconsistency()
    {
        return response()->json([
            'status' => false,
            'message' => 'Data Inconsistency',
            'error_code' => '007',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function badCuil()
    {
        return response()->json([
            'status' => false,
            'message' => 'Internal server problem or bad cuil',
            'error_code' => '008',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function badCaptcha()
    {
        return response()->json([
            'status' => false,
            'message' => 'Bad Captcha',
            'error_code' => '008',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function badEmail()
    {
        return response()->json([
            'status' => false,
            'message' => 'Bad Email',
            'error_code' => '009',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function badPassword()
    {
        return response()->json([
            'status' => false,
            'message' => 'Bad Password',
            'error_code' => '010',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function userRegistered()
    {
        return response()->json([
            'status' => false,
            'message' => 'User already registered',
            'error_code' => '011',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function badCode()
    {
        return response()->json([
            'status' => false,
            'message' => 'Bad confirmation code',
            'error_code' => '011',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function badUser()
    {
        return response()->json([
            'status' => false,
            'message' => 'User Not Found',
            'error_code' => '012',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    
    public function invalidCredentials()
    {
        return response()->json([
            'status' => false,
            'message' => 'Invalid Credentials',
            'error_code' => '013',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function changeName()
    {
        return response()->json([
            'status' => false,
            'message' => 'Not enable to change name',
            'error_code' => '014',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function alreadyVerifiedEmail()
    {
        return response()->json([
            'status' => false,
            'message' => 'Email already verified',
            'error_code' => '015',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function invalidCuil()
    {
        return response()->json([
            'status' => false,
            'message' => 'Invalid Cuil',
            'error_code' => '015',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function noUser()
    {
        return response()->json([
            'status' => false,
            'message' => 'there is not that user',
            'error_code' => '016',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}