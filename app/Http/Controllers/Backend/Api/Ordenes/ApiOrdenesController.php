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
            $orden = Ordenes::where('clientes_id', $request->clienteid)
                ->where('visible', 1)
                ->orderBy('id', 'DESC')
                ->get();

            foreach($orden as $o){
                $o->fecha_orden = date("h:i A d-m-Y", strtotime($o->fecha_orden));

                $infoDireccion = OrdenesDirecciones::where('ordenes_id', $o->id)->first();

                $o->direccion = $infoDireccion->direccion;

                $o->total = number_format((float)$o->precio_consumido, 2, '.', ',');

                if($o->tipoentrega == 1){
                    $entrega = "A Domicilio";
                }else{
                    $entrega = "Pasar a Traer a Local";
                }

                $o->entrega = $entrega;
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

                if($o->estado_2 == 1){ // propietario inicia la orden
                   $o->fecha_2 = date("h:i A d-m-Y", strtotime($o->fecha_2));
                }

                if($o->estado_4 == 1){ // motorista inicia la entrega
                    $o->fecha_4 = date("h:i A d-m-Y", strtotime($o->fecha_4));
                }

                if($o->estado_5 == 1){ // motorista termina la entrega
                    $o->fecha_5 = date("h:i A d-m-Y", strtotime($o->fecha_5));
                }

                if($o->estado_6 == 1){ // cliente finaliza la entrega
                    $o->fecha_6 = date("h:i A d-m-Y", strtotime($o->fecha_6));
                }

                if($o->estado_7 == 1){ // la orden fue cancelada, 1 cliente, 2 propietario
                    $o->fecha_7 = date("h:i A d-m-Y", strtotime($o->fecha_7));
                }

                $o->fecha_orden = date("h:i A d-m-Y", strtotime($o->fecha_orden));
            }

            $mensaje = "Orden Lista. Puede Pasar Al local";

            return ['success' => 1, 'ordenes' => $orden, 'mensaje' => $mensaje];
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

            if($orden->estado_7 == 0){

                // seguro para evitar cancelar cuando servicio inicia a preparar orden
                if($orden->estado_2 == 1){
                    return ['success' => 1];
                }

                DB::beginTransaction();

                try {

                    $fecha = Carbon::now('America/El_Salvador');
                    Ordenes::where('id', $request->ordenid)->update(['estado_7' => 1,
                        'cancelado' => 1,
                        'visible' => 0,
                        'fecha_7' => $fecha]);

                    // notificacion a propietario por orden cancelada por el cliente
                    $listaPropietarios = Afiliados::where('activo', 1)
                        ->where('disponible', 1)
                        ->get();

                    $pilaPropietarios = array();
                    foreach($listaPropietarios as $p){
                        if($p->token_fcm != null){
                            array_push($pilaPropietarios, $p->token_fcm);
                        }
                    }

                    $titulo = "Orden #" . $request->ordenid;
                    $mensaje = "Fue Cancelada por el Cliente";

                    if($pilaPropietarios != null) {
                        SendNotiPropietarioJobs::dispatch($titulo, $mensaje, $pilaPropietarios);
                    }

                    DB::commit();
                    return ['success' => 2];

                } catch(\Throwable $e){
                    DB::rollback();
                    return ['success' => 3];
                }

            }else{
                return ['success' => 2]; // ya cancelada
            }
        }else{
            return ['success' => 3]; // no encontrada
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

    public function borrarOrdenCliente(Request $request){

        // validaciones para los datos
        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        // oculta la orden al cliente
        if(Ordenes::where('id', $request->ordenid)->first()){

            Ordenes::where('id', $request->ordenid)->update(['visible' => 0]);

            return ['success' => 1];
        }else{
            return ['success' => 2]; // no encontrada
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

                $estado = "En proceso";

                if($o->estado_5 == 1){
                    $estado = "Orden Entregada";
                }

                if($o->estado_7 == 1){

                    if($o->cancelado == 2){
                        // propietario
                        if($o->mensaje_7 != null){
                            $estado = "Orden Cancelada: " . $o->mensaje_7;
                        }else{
                            $estado = "Orden Cancelada";
                        }
                    }else{
                        $estado = "Orden Cancelada";
                    }
                }

                $o->estado = $estado;

                $o->total = number_format((float)$o->precio_consumido, 2, '.', ',');

                $infoCliente = OrdenesDirecciones::where('ordenes_id', $o->id)->first();

                if($o->tipoentrega == 1){
                    $o->entrega = "A Domicilio";
                    $o->direccion = $infoCliente->direccion;
                }else{
                    $o->direccion = "";
                    $o->entrega = "Pasar a Traer a Local";
                }
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

            if(MotoristasExperiencia::where('ordenes_id', $or->id)->first()){
                return ['success' => 1]; // ya hay una valoracion
            }

            if($info = MotoristasOrdenes::where('ordenes_id', $or->id)->first()){

                $fecha = Carbon::now('America/El_Salvador');
                $nueva = new MotoristasExperiencia();
                $nueva->ordenes_id = $or->id;
                $nueva->motoristas_id = $info->motoristas_id;
                $nueva->experiencia = $request->valor;
                $nueva->mensaje = $request->mensaje;;
                $nueva->fecha = $fecha;
                $nueva->save();

            }else{

                // la orden fue entregada localmente
                $fecha = Carbon::now('America/El_Salvador');

                $nueva = new MotoristasExperiencia();
                $nueva->ordenes_id = $or->id;
                $nueva->motoristas_id = null;
                $nueva->experiencia = $request->valor;
                $nueva->mensaje = $request->mensaje;;
                $nueva->fecha = $fecha;
                $nueva->save();
            }

            // ocultar orden al usuario
            Ordenes::where('id', $or->id)->update(['visible' => 0]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

}
