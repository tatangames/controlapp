<?php

namespace App\Http\Controllers\Backend\Api\Servicios;

use App\Http\Controllers\Controller;
use App\Models\BloqueServicios;
use App\Models\BloquesEventos;
use App\Models\Categorias;
use App\Models\EventoImagenes;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiServiciosController extends Controller
{
    public function listadoMenuVertical(Request $request){

        $reglaDatos = array(
            'categoria' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(BloqueServicios::where('id', $request->categoria)->first()){

            $productos = Categorias::where('id_bloque_servicios', $request->categoria)
                ->where('activo', 1)
                ->whereHas('productos', function($q){
                    $q->where('activo', 1);
                })
                ->orderBy('posicion', 'ASC')
                ->get();

            $resultsBloque = array();
            $index = 0;

            foreach($productos as $secciones){
                array_push($resultsBloque, $secciones);

                $subSecciones = Producto::where('id_categorias', $secciones->id)
                    ->where('activo', 1)
                    ->orderBy('posicion', 'ASC')
                    ->get();

                $resultsBloque[$index]->productos = $subSecciones;
                $index++;
            }

            return [
                'success' => 1,
                'productos' => $productos,
            ];
        }
        else{
            return ['success' => 2];
        }
    }







    public function listadoEventos(){

        $eventos = BloquesEventos::where('activo', 1)
            ->orderBy('posicion')
            ->get();

        return ['success' => 1, 'eventos' => $eventos];
    }

    public function listadoEventosImagenes(Request $request){

        $eventos = EventoImagenes::where('evento_id', $request->id)
            ->orderBy('posicion')
            ->get();

        $conteo = EventoImagenes::where('evento_id', $request->id)->count();

        return ['success' => 1, 'eventos' => $eventos, 'conteo' => $conteo];
    }

}
