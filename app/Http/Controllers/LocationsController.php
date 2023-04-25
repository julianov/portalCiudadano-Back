<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WebServices\WsEntreRios\EntreRiosWSService;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\ErrorService;
class LocationsController extends Controller
{
	private EntreRiosWSService $wsService;
    private ErrorService $errorService;
    
    public function __construct(EntreRiosWSService $wsService, ErrorService $errorService)
	{

		$this->wsService = $wsService;
        $this->errorService = $errorService;
	}

    public function getLocations()
    {
            
        $rs = $this->wsService->getERLocations();

        if (sizeof($rs) > 1000) {

            return response()->json($rs);
            
        }else{

            return $this->errorService->wsUnavailable();
        
        }
    }

    public function getStringLocations(Request $request):JsonResponse 
    {

        $validator = Validator::make($request->all(), [
            'locality_id' => 'required|numeric',
        ]);

        if($validator->fails()){

            return $this->errorService->badFormat();

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
            return $this->errorService->wsUnavailable();

        }
        
    }
}
