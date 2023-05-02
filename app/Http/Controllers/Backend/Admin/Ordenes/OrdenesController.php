<?php

namespace App\Http\Controllers\Backend\Admin\Ordenes;

use App\Http\Controllers\Controller;
use App\Jobs\SendNotiClienteJobs;
use App\Models\Clientes;
use App\Models\MotoristasExperiencia;
use App\Models\Ordenes;
use App\Models\OrdenesDescripcion;
use App\Models\OrdenesDirecciones;
use App\Models\Producto;
use App\Models\Zonas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdenesController extends Controller
{
    public function index(){
        return view('backend.admin.ordenes.todas.vistaordenes');
    }

    public function tablaOrdenes(){

        $ordenes = Ordenes::orderBy('id', 'DESC')->get();

        foreach ($ordenes as $mm){

            $infocliente = OrdenesDirecciones::where('id', $mm->id)->first();
            $mm->cliente = $infocliente->nombre;

            $mm->fecha_orden = date("h:i A d-m-Y", strtotime($mm->fecha_orden));
            $mm->precio_consumido = number_format((float)$mm->precio_consumido, 2, '.', ',');

            if($infoE = MotoristasExperiencia::where('ordenes_id', $mm->id)->first()){
                $mm->calificacion = "Estrellas: " . $infoE->experiencia . " y Nota es: " . $infoE->mensaje;
            }

            $estado = "Orden Nueva";

           /* if($mm->estado_2 == 1){
                $estado = "Orden Iniciada";
            }

            if($mm->estado_3 == 2){
                $estado = "Orden Terminada";
            }

            if($mm->estado_4 == 1){
                $estado = "Motorista en Camino";
            }

            if($mm->estado_5 == 1){
                $estado = "Orden Entregada";
            }

            if($mm->estado_6 == 1){
                $estado = "Orden Calificada";
            }

            if($mm->estado_7 == 1){

                if($mm->cancelado == 1){
                    $estado = "Orden Cancelada por: Cliente";
                }else{
                    $estado = "Orden Cancelada por: Propietario";
                }
            }*/

            $mm->estado = $estado;
        }

        return view('backend.admin.ordenes.todas.tablaordenes', compact('ordenes'));
    }

    public function informacionOrden(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Ordenes::where('id', $request->id)->first()){

            $cliente = OrdenesDirecciones::where('ordenes_id', $request->id)->get();
            $info = OrdenesDirecciones::where('ordenes_id', $request->id)->first();
            $infoZona = Zonas::where('id', $info->zonas_id)->first();

            return ['success' => 1, 'cliente' => $cliente, 'zona' => $infoZona->nombre];
        }else{
            return ['success' => 2];
        }
    }

    public function indexProductosOrdenes($id){
        return view('backend.admin.ordenes.productos.vistaproductoorden', compact('id'));
    }

    public function tablaOrdenesProducto($id){

        $lista = OrdenesDescripcion::where('ordenes_id', $id)->get();

        foreach ($lista as $ll){

            $info = Producto::where('id', $ll->producto_id)->first();
            $ll->nombre = $info->nombre;

            $total = $ll->cantidad * $ll->precio;
            $ll->total = number_format((float)$total, 2, '.', ',');
            $ll->precio = number_format((float)$ll->precio, 2, '.', ',');
        }

        return view('backend.admin.ordenes.productos.tablaproductoorden', compact('lista'));

    }


    //**** ORDENES PENDIENTES *******

    public function indexOrdenesPendientes(){

        //$dataFecha = Carbon::now('America/El_Salvador');
        //$fecha = date("d-m-Y", strtotime($dataFecha));

        return view('backend.admin.ordenes.pendientes.vistaordenespendientes');
    }

    public function tablaOrdenesPendientes(){
        //$fecha = Carbon::now('America/El_Salvador');

        $ordenes = Ordenes::where('estado_iniciada', 0)
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($ordenes as $mm){

            $clienteDireccion = OrdenesDirecciones::where('id', $mm->id)->first();
            $mm->cliente = $clienteDireccion->nombre;
            $mm->telefono = $clienteDireccion->telefono;
            $mm->direccion = $clienteDireccion->direccion;
            $mm->referencia = $clienteDireccion->punto_referencia;

            $mm->fecha_orden = date("h:i A d-m-Y", strtotime($mm->fecha_orden));
            $mm->precio_consumido = number_format((float)$mm->precio_consumido, 2, '.', ',');
        }

        return view('backend.admin.ordenes.pendientes.tablaordenespendientes', compact('ordenes'));
    }


    public function iniciarOrden(Request $request){

        $rules = array(
            'id' => 'required', // id de la orden
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        $fecha = Carbon::now('America/El_Salvador');

        Ordenes::where('id', $request->id)->update([
            'estado_iniciada' => 1,
            'fecha_iniciada' => $fecha,
        ]);

        $infoOrdenes = Ordenes::where('id', $request->id)->first();
        $infoCliente = Clientes::where('id', $infoOrdenes->clientes_id)->first();

        /*$titulo = "Orden #" . $request->ordenid;
        $mensaje = "Orden Iniciada";

        if($infoCliente->token_fcm != null) {
            SendNotiClienteJobs::dispatch($titulo, $mensaje, $infoCliente->token_fcm);
        }*/

        return ['success' => 1];
    }



    public function verMapaCliente($id){

        $googleapi = config('googleapi.Google_API');

        $infoOrdenes = OrdenesDirecciones::where('ordenes_id', $id)->first();
        $latitud = $infoOrdenes->latitud;
        $longitud = $infoOrdenes->longitud;

        return view('backend.admin.ordenes.mapa.mapaentrega', compact('latitud', 'longitud', 'googleapi'));
    }























    //*****************
    public function indexOrdenHoy(){

        $dataFecha = Carbon::now('America/El_Salvador');
        $fecha = date("d-m-Y", strtotime($dataFecha));

        return view('backend.admin.ordenes.hoy.vistaordeneshoy', compact('fecha'));
    }

    public function tablaOrdenesHoy(){
        $fecha = Carbon::now('America/El_Salvador');
        $ordenes = Ordenes::whereDate('fecha_orden', $fecha)->orderBy('id', 'DESC')->get();

        foreach ($ordenes as $mm){

            $infocliente = OrdenesDirecciones::where('id', $mm->id)->first();
            $mm->cliente = $infocliente->nombre;

            $mm->fecha_orden = date("h:i A d-m-Y", strtotime($mm->fecha_orden));
            $mm->precio_consumido = number_format((float)$mm->precio_consumido, 2, '.', ',');

            if($infoE = MotoristasExperiencia::where('ordenes_id', $mm->id)->first()){
                $mm->calificacion = "Estrellas: " . $infoE->experiencia . " y Nota es: " . $infoE->mensaje;
            }

            $estado = "Orden Nueva";

            if($mm->estado_2 == 1){
                $estado = "Orden Iniciada";
            }

            if($mm->estado_3 == 1){
                $estado = "Orden Terminada";
            }

            if($mm->estado_4 == 1){
                $estado = "Motorista en Camino";
            }

            if($mm->estado_5 == 1){
                $estado = "Orden Entregada";
            }

            if($mm->estado_6 == 1){
                $estado = "Orden Calificada";
            }

            /*if($mm->estado_7 == 1){

                if($mm->cancelado == 1){
                    $estado = "Orden Cancelada por: Cliente";
                }else{
                    $estado = "Orden Cancelada por: Propietario";
                }
            }*/

            $mm->estado = $estado;
        }

        return view('backend.admin.ordenes.hoy.tablaordeneshoy', compact('ordenes'));
    }


}
