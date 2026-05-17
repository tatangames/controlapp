<?php

namespace App\Http\Controllers\Backend\Api\Servicios;

use App\Http\Controllers\Controller;
use App\Models\BloqueServicios;
use App\Models\BloqueSlider;
use App\Models\Clientes;
use App\Models\DireccionCliente;
use App\Models\InformacionAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiZonasServiciosController extends Controller
{
    // obtener listado de servicios
    public function listadoBloque(Request $request){

        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        // retornar bloques de servicios
        $servicios = BloqueServicios::where('activo', 1)
            ->orderBy('posicion', 'ASC')
            ->get();

        return [
            'success' => 1,
            'servicios' => $servicios,
        ];
    }



}
