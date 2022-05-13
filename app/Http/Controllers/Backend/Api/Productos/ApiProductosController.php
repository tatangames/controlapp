<?php

namespace App\Http\Controllers\Backend\Api\Productos;

use App\Http\Controllers\Controller;
use App\Models\CarritoExtra;
use App\Models\CarritoTemporal;
use App\Models\Clientes;
use App\Models\DireccionCliente;
use App\Models\Horario;
use App\Models\Producto;
use App\Models\Zonas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiProductosController extends Controller
{
    public function infoProductoIndividual(Request $request){

        // validaciones para los datos
        $reglaDatos = array(
            'productoid' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if($validarDatos->fails()){return ['success' => 0]; }

        if(Producto::where('id', $request->productoid)->first()){

            $producto = Producto::where('id', $request->productoid)->get();

            return ['success' => 1, 'producto' => $producto];

        }else{
            return ['success' => 2];
        }
    }

    // agregar un producto
    public function agregarProductoCarritoTemporal(Request $request){

        $reglaDatos = array(
            'productoid' => 'required',
            'clienteid' => 'required',
            'cantidad' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos );

        if($validarDatos->fails()){return ['success' => 0]; }


        // primero saber si cliente tiene una direccion
        if(!DireccionCliente::where('clientes_id', $request->clienteid)->first()){
            // agregar una direccion de envio
            return ['success' => 1];
        }

        DB::beginTransaction();

        try {

            // informacion de la direccion del cliente
            $infoDireccion = DireccionCliente::where('clientes_id', $request->clienteid)
                ->where('seleccionado', 1)
                ->first();

            //**** VALIDACIONES

            // validacion de horarios para este servicio
            $numSemana = [
                0 => 1, // domingo
                1 => 2, // lunes
                2 => 3, // martes
                3 => 4, // miercoles
                4 => 5, // jueves
                5 => 6, // viernes
                6 => 7, // sabado
            ];

            // hora y fecha
            $getValores = Carbon::now('America/El_Salvador');
            $getDiaHora = $getValores->dayOfWeek;
            $diaSemana = $numSemana[$getDiaHora];
            $hora = $getValores->format('H:i:s');

            // verificar sin la segunda hora
            $horario = Horario::where('dia', $diaSemana)
                ->where('hora1', '<=', $hora)
                ->where('hora2', '>=', $hora)
                ->get();

            if(count($horario) >= 1){
                // abierto
            }else{
                // cerrado horario normal del servicio (2 horarios)
                return ['success' => 2, 'msj1' => "El negocio esta cerrado por el momento"];
            }

            // preguntar si este dia esta cerrado
            $cerradoHoy = Horario::where('dia', $diaSemana)->first();

            if($cerradoHoy->cerrado == 1){
                // cerrado este dia el negocio
                return ['success' => 3, 'msj1' => "este dia tenemos cerrado"];
            }

            $infoZona = Zonas::where('id', $infoDireccion->zonas_id)->first();

            if($infoZona->saturacion == 1){
                // zona bloqueada por algun problema
                return ['success' => 4, 'msj1' => $infoZona->mensaje_bloqueo];
            }

            // horario delivery para esa zona
            $horarioDeliveryZona = Zonas::where('id', $infoDireccion->zonas_id)
                ->where('hora_abierto_delivery', '<=', $hora)
                ->where('hora_cerrado_delivery', '>=', $hora)
                ->get();

            if(count($horarioDeliveryZona) >= 1){
                // abierto
            }else{
                // cerrado horario de zona
                return ['success' => 5, 'msj1' => "temporalmente cerrado para esta zona el envio"];
            }

            // guardar producto
            $carrito = new CarritoTemporal();
            $carrito->clientes_id = $request->clienteid;
            $carrito->zonas_id = $infoDireccion->zonas_id;
            $carrito->save();

            // guardar producto
            $idcarrito = $carrito->id;
            $extra = new CarritoExtra();
            $extra->carrito_temporal_id = $idcarrito;
            $extra->producto_id = $request->productoid;
            $extra->cantidad = $request->cantidad;
            $extra->nota_producto = $request->notaproducto;
            $extra->save();
            DB::commit();

            return [
                'success' => 6 // producto agregado
            ];

        }catch(\Error $e){
            DB::rollback();

            return [
                'success' => 100
            ];
        }
    }


}
