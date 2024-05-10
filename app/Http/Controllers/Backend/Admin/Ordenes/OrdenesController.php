<?php

namespace App\Http\Controllers\Backend\Admin\Ordenes;

use App\Http\Controllers\Controller;
use App\Jobs\SendNotiClienteJobs;
use App\Models\Clientes;
use App\Models\Ordenes;
use App\Models\OrdenesDescripcion;
use App\Models\OrdenesDirecciones;
use App\Models\Producto;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Ladumor\OneSignal\OneSignal;
use Mpdf\Mpdf;
class OrdenesController extends Controller
{
    public function index(){
        return view('backend.admin.ordenes.todas.vistaordenes');
    }

    // no canceladas
    public function tablaOrdenesTodas(){

        $ordenes = Ordenes::where('estado_cancelada', 0)
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

        return view('backend.admin.ordenes.todas.tablaordenes', compact('ordenes'));
    }


    public function indexOrdenesCanceladas(){

        return view('backend.admin.ordenes.canceladas.vistaordenescanceladas');
    }


    public function tablaOrdenesTodasCanceladas(){
        $ordenes = Ordenes::where('estado_cancelada', 1)
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

        return view('backend.admin.ordenes.canceladas.tablaordenescanceladas', compact('ordenes'));
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
            ->where('estado_cancelada', 0)
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


    public function imprimirTicket($id){

        $infoOrden = Ordenes::where('id', $id)->first();
        $infoDireccion = OrdenesDirecciones::where('id', $id)->first();

        $fecha = date("d-m-Y h:i A", strtotime($infoOrden->fecha_orden));


        $lista = OrdenesDescripcion::where('ordenes_id', $id)->get();

        $suma = 0;

        foreach ($lista as $ll){

            $info = Producto::where('id', $ll->producto_id)->first();

            if($ll->nota != null){
                $ll->nomproducto = $info->nombre . " (" . $ll->nota . ")";
            }
            else{
                $ll->nomproducto = $info->nombre;
            }

            $multiplicado = $ll->cantidad * $ll->precio;
            $suma = $suma + $multiplicado;

            $ll->multiplicado = number_format((float)$multiplicado, 2, '.', ',');
            $ll->precio = number_format((float)$ll->precio, 2, '.', ',');
        }

        $suma = number_format((float)$suma, 2, '.', ',');

        $pdf = PDF::loadView('backend.admin.ticket.vistaticket', compact('infoOrden', 'lista', 'fecha', 'suma', 'infoDireccion'));
        //$pdf->setPaper('b7', 'portrait')->setWarnings(false);

        return $pdf->stream('ticket.pdf');
    }



    public function iniciarOrden(Request $request){

        $rules = array(
            'id' => 'required', // id de la orden
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        $fecha = Carbon::now('America/El_Salvador');


        $infoOrdenes = Ordenes::where('id', $request->id)->first();

        if($infoOrdenes->estado_cancelada == 1){
            return ['success' => 1];
        }

        Ordenes::where('id', $request->id)->update([
            'estado_iniciada' => 1,
            'fecha_iniciada' => $fecha,
        ]);

        $infoCliente = Clientes::where('id', $infoOrdenes->clientes_id)->first();

        //$titulo = "Orden #" . $request->ordenid;
        $mensaje = "Orden Iniciada";

        if($infoCliente->token_fcm != null) {

            $fields['include_player_ids'] = [$infoCliente->token_fcm];
            OneSignal::sendPush($fields, $mensaje);
        }

        return ['success' => 2];
    }


    function cancelarOrden(Request $request){

        $rules = array(
            'id' => 'required', // id de la orden

        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        $fecha = Carbon::now('America/El_Salvador');

        Ordenes::where('id', $request->id)->update([
            'estado_cancelada' => 1,
            'fecha_cancelada' => $fecha,
            'mensaje_cancelada' => $request->nombre,
            'cancelada_por' => 2 // por propietario
        ]);

        $infoOrden = Ordenes::where('id', $request->id)->first();
        $infoCliente = Clientes::where('id', $infoOrden->clientes_id)->first();

        $mensaje = "Lo sentimos, su orden fue cancelada";

        if($infoCliente->token_fcm != null) {

            $fields['include_player_ids'] = [$infoCliente->token_fcm];
            OneSignal::sendPush($fields, $mensaje);
        }

        return ['success' => 1];

    }



    public function verMapaCliente($id){

        $googleapi = config('googleapi.Google_API');

        $infoOrdenes = OrdenesDirecciones::where('ordenes_id', $id)->first();
        $latitud = $infoOrdenes->latitud;
        $longitud = $infoOrdenes->longitud;

        return view('backend.admin.ordenes.mapa.mapaentrega', compact('latitud', 'longitud', 'googleapi'));
    }






    //******* ORDENES INICIADAS HOY ***************
    public function indexOrdenHoy(){

        $dataFecha = Carbon::now('America/El_Salvador');
        $fecha = date("d-m-Y", strtotime($dataFecha));

        return view('backend.admin.ordenes.hoy.vistaordeneshoy', compact('fecha'));
    }

    public function tablaOrdenesHoy(){
        $fecha = Carbon::now('America/El_Salvador');
        $ordenes = Ordenes::whereDate('fecha_orden', $fecha)
            ->where('estado_iniciada', 1)
            ->where('estado_cancelada', 0)
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

        return view('backend.admin.ordenes.hoy.tablaordeneshoy', compact('ordenes'));
    }


}
