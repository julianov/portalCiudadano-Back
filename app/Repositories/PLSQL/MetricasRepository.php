<?php

namespace App\Repositories\PLSQL;

use Illuminate\Support\Facades\DB;

class MetricasRepository
{
    public function __construct()
    {
        // Constructor, si necesitas hacer algo aquí.
    }

    public function obtenerEstadisticasTotales()
    {
        $result = DB::select("SELECT CIUD_METRICAS_PKG.OBTENER_ESTADISTICAS_TOTALES() as result FROM DUAL");
        return $result[0]->result;
    }
}