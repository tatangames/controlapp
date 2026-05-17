<?php

namespace App\Http\Controllers\Backend\Api\Ordenes;

use App\Http\Controllers\Controller;
use App\Jobs\SendNotiPropietarioJobs;
use App\Models\Afiliados;
use App\Models\Clientes;
use App\Models\Ordenes;
use App\Models\OrdenesDescripcion;
use App\Models\OrdenesDirecciones;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiOrdenesController extends Controller
{
    public function ordenesActivas(Request $request){

        // validaciones para los datos
        $reglaDatos = array(
            'clienteid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Clientes::where('id', $request->clienteid)->first()){

            // solo ordenes no canceladas, ni completadas
            $orden = Ordenes::where('id_clientes', $request->clienteid)
                ->where('visible', 1) // la orden finalizada por cliente
                ->orderBy('id', 'DESC')
                ->get();

            foreach($orden as $o){
                $o->fecha_orden = date("h:i A d-m-Y", strtotime($o->fecha_orden));

                $infoDireccion = OrdenesDirecciones::where('id_ordenes', $o->id)->first();

                $o->direccion = $infoDireccion->direccion;

                $o->total = "$" . number_format((float)$o->precio_consumido, 2, '.', ',');

                // prioridad
                if($o->estado_iniciada == 0){
                    $estado = "Orden Pendiente";
                }else{
                    $estado = "Orden Iniciada";
                }

                if($o->estado_cancelada == 1){
                    $estado = "Orden Cancelada";
                }

                $o->estado = $estado;
            }

            if ($orden->isEmpty()) {
                $vacio = 0;
            }else{
                $vacio = 1;
            }

            return ['success' => 1, 'ordenes' => $orden, 'vacio' => $vacio];
        }else{
            return ['success' => 2];
        }
    }

    public function estadoOrdenesActivas(Request $request){

        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Ordenes::where('id', $request->ordenid)->first()){

            $orden = Ordenes::where('id', $request->ordenid)->get();

            foreach($orden as $o){

                if($o->estado_iniciada == 1){
                   $o->fecha_iniciada = date("h:i A d-m-Y", strtotime($o->fecha_iniciada));
                }

                if($o->estado_cancelada == 1){
                    $o->fecha_cancelada = date("h:i A d-m-Y", strtotime($o->fecha_cancelada));
                }

                $o->fecha_orden = date("h:i A d-m-Y", strtotime($o->fecha_orden));
            }

            return ['success' => 1, 'ordenes' => $orden];
        }else{
            return ['success' => 2];
        }
    }

    public function cancelarOrdenCliente(Request $request){

        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($orden = Ordenes::where('id', $request->ordenid)->first()){

            if($orden->estado_iniciada == 1){
                return ['success' => 1, 'titulo' => 'Nota', 'mensaje' => 'No se puede cancelar la orden, ya fue iniciada'];
            }

            if($orden->estado_cancelada == 0){

                // CANCELAR LA ORDEN POR EL CLIENTE
                DB::beginTransaction();

                try {

                    $fecha = Carbon::now('America/El_Salvador');

                    Ordenes::where('id', $request->ordenid)->update(['estado_cancelada' => 1,
                        'cancelada_por' => 1, // CANCELADA POR CLIENTE
                        'visible' => 0,
                        'fecha_cancelada' => $fecha]);

                    DB::commit();

                } catch(\Throwable $e){
                    DB::rollback();
                    return ['success' => 99];
                }

            }
            return ['success' => 2]; // ORDEN CANCELADA
        }else{
            return ['success' => 99]; // ERROR
        }
    }

    public function listadoProductosOrdenes(Request $request){

        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Ordenes::where('id', $request->ordenid)->first()){
            $producto = DB::table('ordenes AS o')
                ->join('ordenes_descripcion AS od', 'od.id_ordenes', '=', 'o.id')
                ->join('producto AS p', 'p.id', '=', 'od.id_producto')
                ->select('od.id AS productoID', 'p.nombre', 'p.utiliza_imagen',
                    'p.imagen', 'od.precio', 'od.cantidad', 'od.nota')
                ->where('o.id', $request->ordenid)
                ->get();

            foreach($producto as $p){
                $cantidad = $p->cantidad;
                $precio = $p->precio;
                $multi = $cantidad * $precio;
                $p->multiplicado = "$" . number_format((float)$multi, 2, '.', ',');
            }

            return ['success' => 1, 'productos' => $producto];
        }else{
            return ['success' => 2];
        }
    }








    public function ocultarOrdenFinal(Request $request){

        $reglaDatos = array(
            'ordenid' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($or = Ordenes::where('id', $request->ordenid)->first()){

            Ordenes::where('id', $or->id)->update(['visible' => 0]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }



    public function completarOrden(Request $request){

        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        Ordenes::where('id', $request->ordenid)->update([
            'visible' => 0
        ]);

        return ['success' => 1];
    }



}
