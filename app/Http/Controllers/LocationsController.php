<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WebServices\WsEntreRios\EntreRiosWSService;
use Illuminate\Support\Facades\Validator;

class LocationsController extends Controller
{
	private EntreRiosWSService $wsService;

    public function __construct(EntreRiosWSService $wsService)
	{

		$this->wsService = $wsService;
	}

    public function getLocations():JsonResponse
    {
            
        $rs = $this->wsService->getERLocations();

        if (sizeof($rs) > 1000) {

            return response()->json($rs);
            
        }else{

            return response()->json([
                'status' => false,
                'message' => 'WS temporarily unavailable'
            ], 503);
        }
        
    }

    public function getStringLocations(Request $request):JsonResponse 
    {

        $validator = Validator::make($request->all(), [
            'locality_id' => 'required',

        ]);

        if($validator->fails()){

            return response()->json([
                'status' => false,
                'message' => 'Bad format'
            ], 503);

        }else{

            $rs = $this->wsService->getERLocations();
        
            foreach ($rs as $item) {
                
                if ($item['ID']== $validator->getData()['locality_id']){

                    return response()->json([
                        'status' => true,
                        'locality' => $item['NOMBRE'],
                        'department' => $item['DEPARTAMENTO'],
                    ], 200);

                }
            }
            return response()->json([
                'status' => false,
                'message' => 'WS temporarily unavailable'
            ], 503);

        }
        
    }
}
