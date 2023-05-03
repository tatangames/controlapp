<?php

namespace App\Http\Controllers\Backend\Api\Servicios;

use App\Http\Controllers\Controller;
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

        // obtener listado de productos, solo sus id de categorias
        $listadoPro = Producto::where('bloque_servicios_id', $request->categoria)->get();
        $pilaIdNombre = array();

        // verificar si esta categoria por horario estara disponible
        foreach ($listadoPro as $ll) {

            array_push($pilaIdNombre, $ll->categorias_id);
        }

        // unicamente las categorias disponibles
        $productos = Categorias::whereIn('id', $pilaIdNombre)->get();

        $resultsBloque = array();
        $index = 0;

        foreach($productos as $secciones){
            array_push($resultsBloque,$secciones);

            $subSecciones = Producto::where('categorias_id', $secciones->id)
                ->where('activo', 1) // para inactivarlo solo para administrador
                ->orderBy('posicion', 'ASC')
                ->get();

            $resultsBloque[$index]->productos = $subSecciones; //agregar los productos en la sub seccion
            $index++;
        }

        return [
            'success' => 1,
            'productos' => $productos,
        ];
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
