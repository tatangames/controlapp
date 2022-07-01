<?php

namespace App\Http\Controllers\Backend\Api\Afiliados;

use App\Http\Controllers\Controller;
use App\Jobs\SendNotiClienteJobs;
use App\Jobs\SendNotiMotoristaJobs;
use App\Models\Afiliados;
use App\Models\Categorias;
use App\Models\Clientes;
use App\Models\Motoristas;
use App\Models\MotoristasExperiencia;
use App\Models\MotoristasOrdenes;
use App\Models\Ordenes;
use App\Models\OrdenesDescripcion;
use App\Models\OrdenesDirecciones;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiCategoriaAfiliadoController extends Controller
{
    public function informacionCategoriasPosiciones(Request $request){

        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if(Afiliados::where('id', $request->id)->first()){

            $categorias = Categorias::orderBy('posicion', 'ASC')
                ->where('activo', 1)
                ->get();

            return ['success'=> 1, 'categorias'=> $categorias];
        }else{
            return ['success'=> 2];
        }
    }


    public function guardarPosicionCategorias(Request $request){

        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if(Afiliados::where('id', $request->id)->first()){

            foreach($request->categoria as $key => $value){

                $posicion = $value['posicion'];

                Categorias::where('id', $key)->update(['posicion' => $posicion]);
            }

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function actualizarDatosCategoria(Request $request){

        $rules = array(
            'id' => 'required',
            'idcategoria' => 'required',
            'nombre' => 'required',
            'valor' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if(Afiliados::where('id', $request->id)->first()){

            if($request->valor == 1){
                // obtener todos los productos de esa categoria
                $pL = Producto::where('categorias_id', $request->idcategoria)->get();

                $bloqueo = true;

                foreach($pL as $lista){
                    if($lista->disponibilidad == 1){ // si hay al menos 1 producto activo, no se desactiva categoria
                        $bloqueo = false;
                    }
                }

                if($bloqueo){
                    $mensaje = "Para activar la categoría, se necesita un producto disponible";
                    return ['success' => 1, 'msj1' => $mensaje];
                }
            }

            // actualizar
            Categorias::where('id', $request->idcategoria)->update(['visible' => $request->valor, 'nombre' => $request->nombre]);

            return ['success'=> 2];
        }else{
            return ['success'=> 0];
        }
    }

    public function listadoProductoPosicion(Request $request){

        $rules = array(
            'id' => 'required',
            'idcategoria' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if(Afiliados::where('id', $request->id)->first()){

            // buscar lista de productos
            $categorias = Producto::where('categorias_id', $request->idcategoria)
                ->where('activo', 1) // activo producto por admin
                ->orderBy('posicion', 'ASC')
                ->get();

            return ['success'=> 1, 'categorias'=> $categorias];
        }else{
            return ['success'=> 2];
        }
    }

    public function actualizarProductosPosicion(Request $request){

        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if(Afiliados::where('id', $request->id)->first()){
            foreach($request->categoria as $key => $value){

                $posicion = $value['posicion'];

                Producto::where('id', $key)->update(['posicion' => $posicion]);
            }
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function listadoCategoriasProducto(Request $request){

        $reglaDatos = array(
            'id' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Afiliados::where('id', $request->id)->first()){

            $lista = Categorias::where('activo', 1)
                ->orderBy('posicion', 'ASC')
                ->get();

            return ['success' => 1, 'categorias' => $lista];
        }else{
            return ['success' => 2];
        }
    }

    public function listadoCategoriasProductoListado(Request $request){

        $reglaDatos = array(
            'id' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Categorias::where('id', $request->id)->first()){

            $lista = Producto::where('categorias_id', $request->id)
                ->orderBy('posicion', 'ASC')
                ->get();

            return ['success' => 1, 'productos' => $lista];
        }else{
            return ['success' => 2];
        }
    }


    public function informacionProductoIndividual(Request $request){

        $rules = array(
            'productoid' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $rules);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Producto::where('id', $request->productoid)->first()){

            $producto = Producto::where('id', $request->productoid)->get();

            return ['success'=> 1, 'productos' => $producto];

        }else{
            return ['success'=> 2];
        }
    }

    public function actualizarProducto(Request $request){
        $rules = array(
            'id' => 'required',
            'productoid' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $rules);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($pp = Afiliados::where('id', $request->id)->first()){

            if(Producto::where('id', $request->productoid)->first()){

                Producto::where('id', $request->productoid)->update(['nombre' => $request->nombre,
                    'descripcion' => $request->descripcion, 'precio' => $request->precio,
                    'nota' => $request->nota, 'disponibilidad' => $request->estadodisponible,
                    'utiliza_nota' => $request->estadonota]);

                return ['success'=> 1];

            }else{
                return ['success'=> 3];
            }
        }else{
            return ['success'=> 3];
        }
    }


    public function informacionEstadoNuevaOrden(Request $request){

        // validaciones para los datos
        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Ordenes::where('id', $request->ordenid)->first()){

            $orden = Ordenes::where('id', $request->ordenid)->get();

            foreach($orden as $o){

                if($o->estado_7 == 1){
                    $o->fecha_7 = date("d-m-Y h:i A", strtotime($o->fecha_7));
                }

                $o->fecha_orden = date("d-m-Y h:i A", strtotime($o->fecha_orden));
            }

            return ['success' => 1, 'ordenes' => $orden];
        }else{
            return ['success' => 2];
        }
    }

    public function listadoProductosOrden(Request $request){

        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        // buscar la orden
        if(Ordenes::where('id', $request->ordenid)->first()){
            $producto = DB::table('ordenes AS o')
                ->join('ordenes_descripcion AS od', 'od.ordenes_id', '=', 'o.id')
                ->join('producto AS p', 'p.id', '=', 'od.producto_id')
                ->select('od.id AS productoID', 'p.nombre', 'od.nota', 'p.utiliza_imagen', 'p.imagen', 'od.precio', 'od.cantidad')
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

    public function listaOrdenProductoIndividual(Request $request){

        $reglaDatos = array(
            'ordenesid' => 'required' // id tabla orden_descripcion
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        // producto descripcion
        if(OrdenesDescripcion::where('id', $request->ordenesid)->first()){

            $producto = DB::table('ordenes_descripcion AS o')
                ->join('producto AS p', 'p.id', '=', 'o.producto_id')
                ->select('p.imagen', 'p.nombre', 'p.descripcion', 'p.nota AS notaproducto', 'p.utiliza_imagen', 'o.precio', 'o.cantidad', 'o.nota')
                ->where('o.id', $request->ordenesid)
                ->get();

            foreach($producto as $p){
                $cantidad = $p->cantidad;
                $precio = $p->precio;
                $multi = $cantidad * $precio;
                $p->multiplicado = number_format((float)$multi, 2, '.', ',');
                $p->descripcion = $p->descripcion;
            }

            return ['success' => 1, 'productos' => $producto];
        }else{
            return ['success' => 2];
        }
    }

    public function cancelarOrden(Request $request){
        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($o = Ordenes::where('id', $request->ordenid)->first()){

            DB::beginTransaction();

            try {

                // el negocio puede cancelar la orden cuando quiera
                if($o->estado_7 == 0){

                    $fecha = Carbon::now('America/El_Salvador');

                    Ordenes::where('id', $request->ordenid)->update(['estado_7' => 1, 'visible_p' => 0,
                        'cancelado' => 2, 'fecha_7' => $fecha, 'mensaje_7' => $request->mensaje]);

                    // notificacion a cliente que su orden fue cancelada
                    $infoCliente = Clientes::where('id', $o->clientes_id)->first();

                    if($infoCliente->token_fcm != null){

                        $titulo = "Orden #" . $request->ordenid . " Cancelada";
                        $mensaje = "Revise su Orden";

                        SendNotiClienteJobs::dispatch($titulo, $mensaje, $infoCliente->token_fcm);
                    }

                    DB::commit();

                    return ['success' => 1];

                }else{
                    return ['success' => 2]; // ya cancelada
                }
            } catch(\Throwable $e){
                DB::rollback();
                return ['success' => 3];
            }
        }else{
            return ['success' => 3]; // no encontrada
        }
    }


    public function borrarOrden(Request $request){

        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Ordenes::where('id', $request->ordenid)->first()){

            Ordenes::where('id', $request->ordenid)->update(['visible_p' => 0]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }


    public function procesarOrdenEstado2(Request $request){

        $reglaDatos = array(
            'ordenid' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($or = Ordenes::where('id', $request->ordenid)->first()){

            // orden fue cancelada
            if($or->estado_7 == 1){
                return ['success' => 1];
            }

            if($or->estado_2 == 0){

                $fecha = Carbon::now('America/El_Salvador');

                Ordenes::where('id', $request->ordenid)->update(['estado_2' => 1,
                    'fecha_2' => $fecha, 'visible_p' => 0, 'visible_p2' => 1, 'visible_p3' => 1]);

                // notificacion a cliente
                $infoCliente = Clientes::where('id', $or->clientes_id)->first();

                if($infoCliente->token_fcm != null){

                    $titulo = "Orden #" . $request->ordenid . " Aceptada";
                    $mensaje = "Su orden inicia su Preparación";

                    SendNotiClienteJobs::dispatch($titulo, $mensaje, $infoCliente->token_fcm);
                }

                // notificacion a motorista que hay orden nueva
                $listaMotoristas = Motoristas::where('activo', 1)
                    ->where('disponible', 1)
                    ->get();

                $pilaMotoristas = array();
                foreach($listaMotoristas as $p){
                    if($p->token_fcm != null){
                        array_push($pilaMotoristas, $p->token_fcm);
                    }
                }

                $titulo = "Hay Nuevas Ordenes";
                $mensaje = "Por Favor Verificar";

                if($pilaMotoristas != null) {
                    SendNotiMotoristaJobs::dispatch($titulo, $mensaje, $pilaMotoristas);
                }

                // orden iniciada
                return ['success' => 2];
            }

            // orden iniciada
            return ['success'=> 2];
        }else{
            return ['success'=> 3];
        }
    }



    public function listadoPreparandoOrdenes(Request $request){

        $rules = array(
            'id' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $rules);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($p = Afiliados::where('id', $request->id)->first()){

            // obtener comision

            $orden = Ordenes::where('estado_7', 0) // ordenes no canceladas
                ->where('visible_p2', 1) // estan en preparacion
                ->where('visible_p3', 1) // aun sin terminar de preparar
                ->where('estado_2', 1) // orden estado 4 preparacion
                ->get();

            foreach($orden as $o) {

                $infoCliente = OrdenesDirecciones::where('ordenes_id', $o->id)->first();

                $o->fecha_orden = date("h:i A d-m-Y", strtotime($o->fecha_orden));
                $o->fecha_2 = date("h:i A d-m-Y", strtotime($o->fecha_2));
                $o->cliente = $infoCliente->nombre;
                $o->telefono = $infoCliente->telefono;

                if($o->tipoentrega == 1){
                    // domicilio
                    $o->direccion = $infoCliente->direccion;
                }else{
                    $o->direccion = "";
                }

                if($o->tipoentrega == 1){
                    $entrega = "A Domicilio";
                }else{
                    $entrega = "Pasar a Traer a Local";
                }

                $o->entrega = $entrega;
                $o->precio_consumido = number_format((float)$o->precio_consumido, 2, '.', ',');
            }

            return ['success' => 1, 'ordenes' => $orden];
        }else{
            return ['success' => 2];
        }
    }

    public function informacionOrdenEnPreparacion(Request $request){

        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Ordenes::where('id', $request->ordenid)->first()){

            $orden = Ordenes::where('id', $request->ordenid)->get();

            return ['success' => 1, 'ordenes' => $orden];
        }else{
            return ['success' => 2];
        }
    }


    public function finalizarOrden(Request $request){

        $reglaDatos = array(
            'ordenid' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($o = Ordenes::where('id', $request->ordenid)->first()){

            $fechahoy = Carbon::now('America/El_Salvador');

            if($o->estado_3 == 0){
                Ordenes::where('id', $request->ordenid)->update(['visible_p2' => 0, 'visible_p3' => 0,
                    'estado_3' => 1, 'fecha_3' => $fechahoy]);
            }

            // notificacion a motorista si agarro la orden
            if($mo = MotoristasOrdenes::where('ordenes_id', $request->ordenid)->first()){

                $info = Motoristas::where('id', $mo->motoristas_id)->first();

                if($info->token_fcm != null){

                    $titulo = "Orden #" . $request->ordenid;
                    $mensaje = "Esta lista para su Entrega";

                    SendNotiMotoristaJobs::dispatch($titulo, $mensaje, $info->token_fcm);
                }
            }

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function listadoOrdenesCompletadasHoy(Request $request){

        $reglaDatos = array(
            'id' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Afiliados::where('id', $request->id)->first()){

            $orden = Ordenes::where('estado_3', 1)
                ->whereDate('fecha_orden', '=', Carbon::today('America/El_Salvador')->toDateString())
                ->get();

            foreach($orden as $o){

                $infoOrden = OrdenesDirecciones::where('ordenes_id', $o->id)->first();

                $o->fecha_orden = date("h:i A ", strtotime($o->fecha_orden));
                if($o->fecha_3 != null){
                    $o->fecha_3 = date("h:i A ", strtotime($o->fecha_3));
                }

                if($o->fecha_5 != null){
                    $o->fecha_5 = date("h:i A ", strtotime($o->fecha_5));
                }

                $o->cliente = $infoOrden->nombre;
                $o->direccion = $infoOrden->direccion;
                $o->telefono = $infoOrden->telefono;

                $o->precio_consumido = number_format((float)$o->precio_consumido, 2, '.', ',');

                $calificada = "";
                $motorista = "";

                if($infoC = MotoristasExperiencia::where('ordenes_id', $o->id)->first()){
                    if($infoC->mensaje != null) {
                        $calificada = "#" . $infoC->experiencia . " | " . $infoC->mensaje;
                    }else{
                        $calificada = "#" . $infoC->experiencia;
                    }
                }


                $o->calificada = $calificada;
                $o->motorista = $motorista;

            }

            return ['success' => 1, 'ordenes' => $orden];
        }else{
            return ['success' => 2];
        }
    }


    // historial de ordenes propietarios
    public function historialOrdenesCompletas(Request $request){
        $reglaDatos = array(
            'id' => 'required',
            'fecha1' => 'required',
            'fecha2' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if($p = Afiliados::where('id', $request->id)->first()){

            $date1 = Carbon::parse($request->fecha1)->startOfDay();
            $date2 = Carbon::parse($request->fecha2)->endOfDay();

            // todas las ordenes por fecha
            $orden = Ordenes::whereBetween('fecha_orden', array($date1, $date2))->get();

            $conteoOrden = 0;
            $vendido = 0;
            foreach($orden as $o){
                $conteoOrden++;

                $o->fecha_orden = date("d-m-Y h:i A", strtotime($o->fecha_orden));

                $vendido = $vendido + $o->precio_consumido;
                $o->precio_consumido = number_format((float)$o->precio_consumido, 2, '.', ',');
                $infoCliente = OrdenesDirecciones::where('ordenes_id', $o->id)->first();
                $o->cliente = $infoCliente->nombre;
                $o->telefono = $infoCliente->telefono;

                if($o->tipoentrega == 1){
                    $entrega = "A Domicilio";
                    $o->direccion = $infoCliente->direccion;
                    $o->puntoref = $infoCliente->punto_referencia;
                }else{
                    $entrega = "Pasar a Traer a Local";
                    $o->direccion = "";
                    $o->puntoref = "";
                }

                $o->entrega = $entrega;
            }

            $vendido = number_format((float)$vendido, 2, '.', ',');

            return ['success' => 1, 'ordenes' => $orden,
                'conteo' => $conteoOrden,
                'total' => $vendido];
        }else{
            return ['success' => 2];
        }
    }

    public function actualizarCantidadProducto(Request $request){

        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if($info = OrdenesDescripcion::where('id', $request->id)->first()){

            if($info->cantidad != $request->cantidad){
                OrdenesDescripcion::where('id', $request->id)->update(['cantidad' => $request->cantidad]);


                // obtener todos los productos de la orden
                $todos = OrdenesDescripcion::where('ordenes_id', $info->ordenes_id)->get();
                $sumado = 0;

                // multiplicar precio x cantidad
                foreach($todos as $p){
                    $multi = $p->cantidad * $p->precio;
                    $sumado = $sumado + $multi;
                }

                // setear consumo
                Ordenes::where('id', $info->ordenes_id)->update(['precio_consumido' => $sumado]);
            }

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function infoCategoriaHorario(Request $request){

        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if($info = Categorias::where('id', $request->id)->first()){

            $categoria = Categorias::where('id', $request->id)->get();

            $hora1 = date("h:i A", strtotime($info->hora1));
            $hora2 = date("h:i A", strtotime($info->hora2));

            return ['success' => 1, 'horario' => $categoria,
                'hora1' => $hora1, 'hora2' => $hora2];
        }else{
            return ['success' => 2];
        }
    }


    public function guardarCategoriaHorario(Request $request){

        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if(Categorias::where('id', $request->id)->first()){

            Categorias::where('id', $request->id)
                ->update(['usahorario' => $request->estado,
                    'hora1' => $request->hora1,
                    'hora2' => $request->hora2]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

}
