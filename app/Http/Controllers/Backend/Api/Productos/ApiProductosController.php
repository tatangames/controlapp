<?php

namespace App\Http\Controllers\Backend\Api\Productos;

use App\Http\Controllers\Controller;
use App\Models\CarritoExtra;
use App\Models\CarritoTemporal;
use App\Models\Clientes;
use App\Models\DireccionCliente;
use App\Models\Horario;
use App\Models\InformacionAdmin;
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

        DB::beginTransaction();

        try {

            if($infoC = CarritoTemporal::where('id_clientes', $request->clienteid)->first()){
                $extra = new CarritoExtra();
                $extra->id_carrito_temporal = $infoC->id;
                $extra->id_producto = $request->productoid;
                $extra->cantidad = $request->cantidad;
                $extra->nota_producto = $request->notaproducto;
                $extra->save();
            }else{
                // guardar producto
                $carrito = new CarritoTemporal();
                $carrito->id_clientes = $request->clienteid;
                $carrito->save();

                // guardar producto
                $idcarrito = $carrito->id;
                $extra = new CarritoExtra();
                $extra->id_carrito_temporal = $idcarrito;
                $extra->id_producto = $request->productoid;
                $extra->cantidad = $request->cantidad;
                $extra->nota_producto = $request->notaproducto;
                $extra->save();
            }

            DB::commit();

            // producto guardado
            return ['success' => 1];

        }catch(\Error $e){
            DB::rollback();

            return [
                'success' => 100
            ];
        }
    }


}
