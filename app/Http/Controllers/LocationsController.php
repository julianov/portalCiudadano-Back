<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WebServices\WsEntreRios\EntreRiosWSService;

class LocationsController extends Controller
{
	private EntreRiosWSService $wsService;

    public function __construct(EntreRiosWSService $wsService)
	{

		$this->wsService = $wsService;
	}

    public function getLocations()
    {
        $rs = $this->wsService->getERLocations();
        return response()->json($rs);
    }
}
