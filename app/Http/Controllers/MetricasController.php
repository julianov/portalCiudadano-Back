<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PLSQL\MetricasRepository;
use App\Http\Services\ErrorService;

use DB;

class MetricasController extends Controller
{
    protected MetricasRepository $repository;
    private ErrorService $errorService;

    public function __construct(MetricasRepository $repository, ErrorService $errorService)
	{

		$this->repository = $repository;
        $this->errorService = $errorService;

	}

    public function metrics(Request $request)
    {
        $this->repository->
        $data = $repo->obtenerEstadisticasTotales();
        return response()->json($data);
    }
}