<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationsController extends Controller
{

    public function newNotification(Request $request){

        $request->validate([
            "token" => "required|string",
        ]);

        $host = env('BASEUR_ER_WS_TOKEN');

        $data = [
            '_tk' => $request['token']
           ];

        $response = $client->request('POST', $host, [
            'headers' => [
            'Content-Type' => 'application/json'
        ],
            'json' => $data
        ]);

        if($response == 0){

        }else{

            return response()->json([
                'status' => false,
                'message' => 'Bad token'
            ], 401);

        }
    }

}
