<?php

namespace App\Http\Controllers\Backend\Admin\Control;

use App\Http\Controllers\Controller;
use App\Models\Clientes;
use App\Models\Ordenes;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EstadisticasController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){

        // clientes registrados hoy
        $fecha = Carbon::now('America/El_Salvador');

        $clientehoy = Clientes::whereDate('fecha', $fecha)->count();

        // total de clientes
        $clientetotal = Clientes::count();

        // total de ordenes aprobadas
        $tordenes = Ordenes::where('estado_iniciada', 1)
            ->where('estado_cancelada', 0)
            ->count();

        // venta total de ordenes no canceladas
        $vtotal = Ordenes::where('estado_iniciada', 1)
            ->where('estado_cancelada', 0)
            ->sum('precio_consumido');

        $vtotal = number_format((float)$vtotal, 2, '.', ',');

        return view('backend.admin.estadisticas.vistaestadisticas', compact('clientehoy', 'clientetotal',
        'tordenes', 'vtotal'));
    }
}
