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

        $getValores = Carbon::now('America/El_Salvador');
        $hora = $getValores->format('H:i:s');

        // se necesita verificar que categorias utilizan horarios para mostrarse y crear una lista de Id
        // obtener lista de id que utilizan horarios
        $listaIdHorario = Categorias::where('bloqueservicio_id', $request->categoria)
        ->where('usahorario', 1)->get();
        $pilaIdDisponible = array();

        // verificar si esta categoria por horario estara disponible
        foreach ($listaIdHorario as $ll){
            $detalle = Categorias::where('id', $ll->id)
                ->where('hora1', '<=', $hora)
                ->where('hora2', '>=', $hora)
                ->get();

            if(count($detalle) >= 1){
                // abierto
                array_push($pilaIdDisponible, $ll->id);
            }else{
                // cerrado
            }
        }

        // obtener todos los id categorias que no utilizan horario
        $listaNoHorario = Categorias::where('bloqueservicio_id', $request->categoria)
        ->where('usahorario', 0)->get();

        // meter estos id a la lista tambien
        foreach ($listaNoHorario as $ln){
            array_push($pilaIdDisponible, $ln->id);
        }

        // unicamente las categorias disponibles
        $productos = Categorias::whereIn('id', $pilaIdDisponible)
            ->where('activo', 1) // app cliente y afiliado
            ->where('visible', 1) // app cliente
            ->where('bloqueservicio_id', $request->categoria)
            ->orderBy('posicion', 'ASC')
            ->get();

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
