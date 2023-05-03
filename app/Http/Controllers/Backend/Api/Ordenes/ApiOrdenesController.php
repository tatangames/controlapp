<?php

namespace App\Http\Controllers\Backend\Api\Ordenes;

use App\Http\Controllers\Controller;
use App\Jobs\SendNotiPropietarioJobs;
use App\Models\Afiliados;
use App\Models\Clientes;
use App\Models\MotoristasExperiencia;
use App\Models\MotoristasOrdenes;
use App\Models\Ordenes;
use App\Models\OrdenesDescripcion;
use App\Models\OrdenesDirecciones;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $orden = Ordenes::where('clientes_id', $request->clienteid)
                ->where('visible', 1) // la orden finalizada por cliente
                ->orderBy('id', 'DESC')
                ->get();

            foreach($orden as $o){
                $o->fecha_orden = date("h:i A d-m-Y", strtotime($o->fecha_orden));

                $infoDireccion = OrdenesDirecciones::where('ordenes_id', $o->id)->first();

                $o->direccion = $infoDireccion->direccion;

                $o->total = number_format((float)$o->precio_consumido, 2, '.', ',');

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

            return ['success' => 1, 'ordenes' => $orden];
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

                if($o->estado_iniciada == 1){ // propietario inicia la orden
                   $o->fecha_iniciada = date("h:i A d-m-Y", strtotime($o->fecha_iniciada));
                }

                if($o->estado_cancelada == 1){ // motorista inicia la entrega
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

            if($orden->estado_cancelada == 0){

                // seguro para evitar cancelar cuando servicio inicia a preparar orden
                if($orden->estado_iniciada == 1){
                    return ['success' => 1];
                }

                DB::beginTransaction();

                try {

                    $fecha = Carbon::now('America/El_Salvador');

                    Ordenes::where('id', $request->ordenid)->update(['estado_cancelada' => 1,
                        'cancelada_por' => 1,
                        'visible' => 0,
                        'fecha_cancelada' => $fecha]);

                    DB::commit();
                    return ['success' => 2];

                } catch(\Throwable $e){
                    DB::rollback();
                    return ['success' => 99];
                }

            }else{
                return ['success' => 2]; // ya cancelada
            }
        }else{
            return ['success' => 99]; // no encontrada
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
                ->join('ordenes_descripcion AS od', 'od.ordenes_id', '=', 'o.id')
                ->join('producto AS p', 'p.id', '=', 'od.producto_id')
                ->select('od.id AS productoID', 'p.nombre', 'p.utiliza_imagen', 'p.imagen', 'od.precio', 'od.cantidad')
                ->where('o.id', $request->ordenid)
                ->get();

            foreach($producto as $p){
                $cantidad = $p->cantidad;
                $precio = $p->precio;
                $multi = $cantidad * $precio;
                $p->multiplicado = number_format((float)$multi, 2, '.', ',');
            }

            return ['success' => 1, 'productos' => $producto];
        }else{
            return ['success' => 2];
        }
    }

    public function listadoProductosOrdenesIndividual(Request $request){

        $reglaDatos = array(
            'productoid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(OrdenesDescripcion::where('id', $request->productoid)->first()){

            $producto = DB::table('ordenes_descripcion AS o')
                ->join('producto AS p', 'p.id', '=', 'o.producto_id')
                ->select('p.imagen', 'p.nombre', 'p.descripcion', 'p.utiliza_imagen', 'o.precio', 'o.cantidad', 'o.nota')
                ->where('o.id', $request->productoid)
                ->get();

            foreach($producto as $p){
                $cantidad = $p->cantidad;
                $precio = $p->precio;
                $multi = $cantidad * $precio;
                $p->multiplicado = number_format((float)$multi, 2, '.', ',');
            }

            return ['success' => 1, 'productos' => $producto];
        }else{
            return ['success' => 2];
        }
    }


    public function verHistorial(Request $request){
        $reglaDatos = array(
            'id' => 'required',
            'fecha1' => 'required',
            'fecha2' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos );

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Clientes::where('id', $request->id)->first()){

            $start = Carbon::parse($request->fecha1)->startOfDay();
            $end = Carbon::parse($request->fecha2)->endOfDay();

            $orden = Ordenes::where('clientes_id', $request->id)
                ->whereBetween('fecha_orden', [$start, $end])
                ->orderBy('id', 'DESC')
                ->get();

            foreach($orden as $o){

                $o->fecha_orden = date("h:i A d-m-Y", strtotime($o->fecha_orden));

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



                $o->total = number_format((float)$o->precio_consumido, 2, '.', ',');


                $infoCliente = OrdenesDirecciones::where('ordenes_id', $o->id)->first();

               $o->direccion = $infoCliente->direccion;
            }

            return ['success' => 1, 'historial' => $orden];

        }else{
            return ['success' => 2];
        }
    }

    public function verProductosOrdenHistorial(Request $request){
        // validaciones para los datos
        $reglaDatos = array(
            'ordenid' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos );

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Ordenes::where('id', $request->ordenid)->first()){

            $producto = DB::table('ordenes AS o')
                ->join('ordenes_descripcion AS od', 'od.ordenes_id', '=', 'o.id')
                ->join('producto AS p', 'p.id', '=', 'od.producto_id')
                ->select('od.id AS productoID', 'p.nombre', 'od.nota',
                    'p.imagen', 'p.utiliza_imagen', 'od.precio', 'od.cantidad')
                ->where('o.id', $request->ordenid)
                ->get();

            foreach($producto as $p){
                $cantidad = $p->cantidad;
                $precio = $p->precio;
                $multi = $cantidad * $precio;
                $p->multiplicado = number_format((float)$multi, 2, '.', ',');
            }
            return ['success' => 1, 'productos' => $producto];
        }else{
            return ['success' => 3];
        }
    }

    public function calificarEntrega(Request $request){

        $reglaDatos = array(
            'ordenid' => 'required',
            'valor' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($or = Ordenes::where('id', $request->ordenid)->first()){

            Ordenes::where('id', $or->id)
                ->update(['estrellas' => $request->valor,
                    'mensaje_estrellas' => $request->mensaje,
                    'visible' => 0]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

}
