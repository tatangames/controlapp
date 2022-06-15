<?php

namespace App\Http\Controllers\Backend\Api\Motoristas;

use App\Http\Controllers\Controller;
use App\Jobs\SendNotiClienteJobs;
use App\Models\Clientes;
use App\Models\Motoristas;
use App\Models\MotoristasOrdenes;
use App\Models\Ordenes;
use App\Models\OrdenesDirecciones;
use App\Models\Zonas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiMotoristasController extends Controller
{
    public function loginMotorista(Request $request){
        $rules = array(
            'usuario' => 'required',
            'password' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $rules);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($p = Motoristas::where('usuario', $request->usuario)->first()){

            if($p->activo == 0){
                return ['success' => 1]; // motorista no activo
            }

            if (Hash::check($request->password, $p->password)) {

                $id = $p->id;
                if($request->token_fcm != null){
                    Motoristas::where('id', $p->id)->update(['token_fcm' => $request->token_fcm]);
                }

                // disponible
                Motoristas::where('id', $p->id)->update(['disponible' => 1]);

                return ['success' => 2, 'id' => $id];
            }
            else{
                return ['success' => 3];
            }
        }else{
            return ['success' => 4];
        }
    }

    public function verNuevasOrdenes(Request $request){

        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if($m = Motoristas::where('id', $request->id)->first()){

            if($m->activo == 0){
                return ['success' => 1];
            }

            $noquiero = DB::table('motoristas_ordenes AS mo')->get();

            $pilaOrden = array();
            foreach($noquiero as $p){
                array_push($pilaOrden, $p->ordenes_id);
            }

            $orden = Ordenes::where('estado_5', 0)
                ->where('estado_2', 1) // inicia la orden
                ->where('estado_7', 0) // orden no cancelada
                ->whereNotIn('id', $pilaOrden)
                ->get();

            foreach($orden as $o){

                $infoDireccion = OrdenesDirecciones::where('ordenes_id', $o->id)->first();
                $o->cliente = $infoDireccion->nombre;
                $o->direccion = $infoDireccion->direccion;
                $o->telefono = $infoDireccion->telefono;

                $o->precio_consumido = number_format((float)$o->precio_consumido, 2, '.', ',');

                $o->fecha_orden = date("h:i A d-m-Y", strtotime($o->fecha_orden));
            }

            return ['success' => 2, 'ordenes' => $orden];
        }else{
            return ['success' => 3];
        }
    }

    public function verOrdenPorID(Request $request){

        // validaciones para los datos
        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($info = Ordenes::where('id', $request->ordenid)->first()){

            //sacar direccion de la orden
            $orden = OrdenesDirecciones::where('ordenes_id', $request->ordenid)->get();

            $venta = number_format((float)$info->precio_consumido, 2, '.', ',');

            return ['success' => 1, 'cliente' => $orden, 'venta' => $venta];
        }else{
            return ['success' => 2];
        }
    }

    public function verProductosOrden(Request $request){
        // validaciones para los datos
        $reglaDatos = array(
            'ordenid' => 'required'
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

    public function obtenerOrden(Request $request){

        // validaciones para los datos
        $reglaDatos = array(
            'ordenid' => 'required',
            'id' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Motoristas::where('id', $request->id)->first()){

            if($or = Ordenes::where('id', $request->ordenid)->first()){

                DB::beginTransaction();
                try {
                    if(MotoristasOrdenes::where('ordenes_id', $request->ordenid)->first()){
                        // orden ya lo tiene un motorista
                        return ['success' => 1];
                    }

                    if($or->estado_7 == 1){
                        // orden cancelada
                        return ['success' => 2];
                    }

                    // ACTUALIZAR
                    Ordenes::where('id', $request->ordenid)->update(['visible_m' => 1]);
                    $fecha = Carbon::now('America/El_Salvador');

                    $nueva = new MotoristasOrdenes();
                    $nueva->ordenes_id = $or->id;
                    $nueva->motoristas_id = $request->id;
                    $nueva->fecha_agarrada = $fecha;
                    $nueva->save();

                    DB::commit();

                    return ['success' => 3];

                } catch(\Throwable $e){
                    DB::rollback();
                    return ['success' => 5];
                }

            }else{
                return ['success' => 5]; // orden no encontrada
            }
        }else{
            return ['success' => 5]; // motorista no encontrado
        }
    }


    public function verProcesoOrdenes(Request $request){

        // validaciones para los datos
        $reglaDatos = array(
            'id' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Motoristas::where('id', $request->id)->first()){

            // mostrar si fue cancelada para despues setear visible_m

            $orden = DB::table('motoristas_ordenes AS mo')
                ->join('ordenes AS o', 'o.id', '=', 'mo.ordenes_id')
                ->select('o.id', 'o.precio_consumido', 'o.fecha_4',
                    'o.estado_5', 'o.estado_6',
                    'o.estado_7', 'o.visible_m', 'o.nota', 'o.fecha_orden')
                ->where('o.estado_6', 0) // aun sin entregar al cliente
                ->where('o.visible_m', 1) // para ver si una orden fue cancelada, y el motorista la agarro, asi ver el estado
                ->where('o.estado_4', 0) // aun no han salido a entregarse
                ->where('mo.motoristas_id', $request->id)
                ->get();

            // sumar mas envio
            foreach($orden as $o) {

                $o->precio_consumido = number_format((float)$o->precio_consumido, 2, '.', ',');

                $o->fecha_orden = date("d-m-Y h:i A", strtotime($o->fecha_orden));
            }

            return ['success' => 1, 'ordenes' => $orden];
        }else{
            return ['success' => 2];
        }
    }


    public function verOrdenProcesoPorID(Request $request){

        // validaciones para los datos
        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($or = Ordenes::where('id', $request->ordenid)->first()){

            //sacar direccion de la orden

            $orden = OrdenesDirecciones::where('ordenes_id', $request->ordenid)->get();

            // titulo que dira la notificacion, cuando se alerte al cliente que esta llegando su pedido.
            $mensaje = "Su orden #" . $request->ordenid . " esta llegando";

            return ['success' => 1, 'cliente' => $orden,
                'estado' => $or->estado_6,
                'cancelado' => $or->estado_7,
                'mensaje' => $mensaje];
        }else{
            return ['success' => 2];
        }
    }

    public function iniciarEntrega(Request $request){

        // validaciones para los datos
        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($or = Ordenes::where('id', $request->ordenid)->first()){

            if($or->estado_7 == 1){
                return ['success' => 1];
            }
            // orden ya fue preparada por el propietario
            if($or->estado_3 == 1 && $or->estado_5 == 0){

                $fecha = Carbon::now('America/El_Salvador');

                Ordenes::where('id', $request->ordenid)->update(['estado_4' => 1,
                    'fecha_4' => $fecha]);

                // notificacion al cliente que la orden va en camino;
                $infoCliente = Clientes::where('id', $or->clientes_id)->first();

                if($infoCliente->token_fcm != null){

                    $titulo = "Orden #" . $request->ordenid . " En Camino";
                    $mensaje = "El Motorista se Dirige a su Dirección";

                    SendNotiClienteJobs::dispatch($titulo, $mensaje, $infoCliente->token_fcm);
                }

                return ['success' => 2]; //orden va en camino
            }else{
                return ['success' => 3]; // la orden aun no ha sido preparada
            }
        }else{
            return ['success' => 4];
        }
    }

    public function informacionDisponibilidad(Request $request){
        $rules = array(
            'id' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $rules);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($p = Motoristas::where('id', $request->id)->first()){

            return ['success'=> 1, 'disponibilidad' => $p->disponible];
        }else{
            return ['success'=> 2];
        }
    }

    public function modificarDisponibilidad(Request $request){
        $rules = array(
            'id' => 'required',
            'valor' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $rules);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Motoristas::where('id', $request->id)->first()){

            Motoristas::where('id', $request->id)->update(['disponible' => $request->valor]);

            return ['success'=> 1];
        }else{
            return ['success'=> 2]; // motorista no encontrado
        }
    }

    public function informacionCuenta(Request $request){
        $rules = array(
            'id' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $rules);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($p = Motoristas::where('id', $request->id)->first()){

            return ['success'=> 1, 'nombre' => $p->nombre];
        }else{
            return ['success'=> 2];
        }
    }

    public function actualizarPassword(Request $request){
        $rules = array(
            'id' => 'required',
            'password' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $rules);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Motoristas::where('id', $request->id)->first()){

            Motoristas::where('id', $request->id)->update(['password' => Hash::make($request->password)]);

            return ['success'=> 1];
        }else{
            return ['success'=> 2];
        }
    }

    public function verProcesoOrdenesEntrega(Request $request){

        // validaciones para los datos
        $reglaDatos = array(
            'id' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Motoristas::where('id', $request->id)->first()){

            // mostrar si fue cancelada para despues setear visible_m

            $orden = DB::table('motoristas_ordenes AS mo')
                ->join('ordenes AS o', 'o.id', '=', 'mo.ordenes_id')
                ->select('o.id', 'o.precio_consumido', 'o.fecha_4',
                    'o.estado_7', 'o.visible_m', 'o.fecha_orden',
                    'o.nota')
                ->where('o.estado_4', 1) // motorista inicio entrega
                ->where('o.estado_7', 0) // orden no cancelada
                ->where('o.visible_m', 1) // para ver si una orden fue cancelada, y el motorista la agarro, asi ver el estado
                ->where('mo.motoristas_id', $request->id)
                ->get();

            // sumar mas envio
            foreach($orden as $o){
                $o->fecha_orden = date("h:i A d-m-Y", strtotime($o->fecha_orden));
                $o->total = number_format((float)$o->precio_consumido, 2, '.', ',');
            }

            return ['success' => 1, 'ordenes' => $orden];
        }else{
            return ['success' => 2];
        }
    }

    public function finalizarEntrega(Request $request){

        // validaciones para los datos
        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($or = Ordenes::where('id', $request->ordenid)->first()){

            // si al orden no ha sido cancelada
            if($or->estado_7 == 1){
                return ['success' => 1];
            }

            $fecha = Carbon::now('America/El_Salvador');

            Ordenes::where('id', $request->ordenid)->update(['estado_5' => 1,
                'fecha_5' => $fecha, 'visible_m' => 0]);

            $infoCliente = Clientes::where('id', $or->clientes_id)->first();

            if($infoCliente->token_fcm != null){

                $titulo = "Orden #" . $request->ordenid . " Entregada";
                $mensaje = "Muchas Gracias.";

                SendNotiClienteJobs::dispatch($titulo, $mensaje, $infoCliente->token_fcm);
            }

            return ['success' => 2]; // orden completada
        }else{
            return ['success' => 3];
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

        if(Motoristas::where('id', $request->id)->first()){

            $start = Carbon::parse($request->fecha1)->startOfDay();
            $end = Carbon::parse($request->fecha2)->endOfDay();

            $orden = DB::table('motoristas_ordenes AS m')
                ->join('ordenes AS o', 'o.id', '=', 'm.ordenes_id')
                ->select('o.id', 'o.precio_consumido', 'o.fecha_orden',
                    'm.motoristas_id', 'o.estado_7', 'o.nota', 'o.estado_5')
                ->where('m.motoristas_id', $request->id) // del motorista
                ->whereBetween('o.fecha_orden', [$start, $end])
                ->orderBy('o.id', 'DESC')
                ->get();

            $totalOrdenes = 0;
            $totalCobrado = 0; // precio envio + venta
            foreach($orden as $o){
                $totalOrdenes++;

                $o->fecha_orden = date("h:i A d-m-Y", strtotime($o->fecha_orden));

                if($o->estado_5 == 1){
                    $o->estado = "Orden Entregada";
                }
                else if($o->estado_7 == 1){
                    $o->estado = "Orden Cancelada";
                }else{
                    $o->estado = "";
                }

                $totalCobrado = $totalCobrado + $o->precio_consumido;
                $o->total = number_format((float)$o->precio_consumido, 2, '.', ',');

                $infoCliente = OrdenesDirecciones::where('ordenes_id', $o->id)->first();

                $o->cliente = $infoCliente->nombre;
                $o->direccion = $infoCliente->direccion;
                $o->puntoref = $infoCliente->punto_referencia;
                $o->telefono = $infoCliente->telefono;

                $infoZona = Zonas::where('id', $infoCliente->zonas_id)->first();

                $o->zona = $infoZona->nombre;
            }

            $totalCobrado = number_format((float)$totalCobrado, 2, '.', ',');

            return ['success' => 1,
                'historial' => $orden,
                'ventaenvio' => $totalCobrado,
                'conteo' => $totalOrdenes
            ];

        }else{
            return ['success' => 2];
        }
    }


}
