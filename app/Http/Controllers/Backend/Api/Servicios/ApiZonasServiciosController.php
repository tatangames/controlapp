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

        if($data = Clientes::where('id', $request->id)->first()){
            if($data->activo == 0){

                $mensaje = "Usuario ha sido bloqueado. Contactar a la administración";

                // bloquear usuario
                return ['success' => 1, 'mensaje' => $mensaje];
            }
        }

        // retornar bloques de servicios
        $servicios = BloqueServicios::where('activo', 1)
            ->orderBy('posicion', 'ASC')
            ->get();

        $slider = BloqueSlider::orderBy('posicion')->get();
        $lleva = false;
        foreach ($slider as $ss){
            $lleva = true;

            if($ss->redireccionamiento == 0){
                $ss->id_producto = 0;
            }

            if($ss->id_producto == null){
                $ss->id_producto = 0;
            }
        }

        $infogeneral = InformacionAdmin::where('id', 1)->first();
        $visibleSlider = $infogeneral->activo_slider;

        if($lleva == false){
           $visibleSlider = 0;
        }

        return [
            'success' => 2,
            'servicios' => $servicios,
            'slider' => $slider,
            'activo_slider' => $visibleSlider
        ];
    }

}
