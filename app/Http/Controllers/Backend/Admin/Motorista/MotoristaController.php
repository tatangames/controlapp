<?php

namespace App\Http\Controllers\Backend\Admin\Motorista;

use App\Http\Controllers\Controller;
use App\Models\Motoristas;
use App\Models\MotoristasOrdenes;
use App\Models\Ordenes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MotoristaController extends Controller
{
    public function index(){
        return view('backend.admin.motoristas.vistamotorista');
    }

    // tabla
    public function tablaMotoristas(){
        $motoristas = Motoristas::orderBy('nombre')->get();

        return view('backend.admin.motoristas.tablamotorista', compact('motoristas'));
    }

    public function nuevo(Request $request){

        $regla = array(
            'nombre' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(Motoristas::where('usuario', $request->usuario)->first()){
            return ['success' => 1];
        }

        $p = new Motoristas();
        $p->nombre = $request->nombre;
        $p->usuario = $request->usuario;
        $p->password = bcrypt($request->password);
        $p->token_fcm = null;
        $p->disponible = 0;
        $p->activo = 1;

        if($p->save()){
            return ['success' => 2];
        }else{
            return ['success' => 3];
        }
    }

    public function informacion(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($p = Motoristas::where('id', $request->id)->first()){

            return ['success' => 1, 'afiliado' => $p];
        }else{
            return ['success' => 2];
        }
    }

    public function editar(Request $request){
        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){ return ['success' => 0];}

        if(Motoristas::where('id', $request->id)->first()){

            if(Motoristas::where('usuario', $request->usuario)->where('id', '!=', $request->id)->first()){
                return [
                    'success' => 1
                ];
            }

            Motoristas::where('id', $request->id)->update([
                'nombre' => $request->nombre,
                'usuario' => $request->usuario,
                'activo' => $request->activo
            ]);

            // actualizar password
            if($request->passcheck == 1){
                Motoristas::where('id', $request->id)->update([
                    'password' => bcrypt('12345678')
                ]);
            }

            return ['success' => 2];
        }else{
            return ['success' => 3];
        }
    }


    // ***** MOTORISTAS ORDENES *****

    public function indexMotoristaOrdenes(){
        return view('backend.admin.motoristas.ordenes.vistamotoristaordenes');
    }

    public function tablaMotoristasOrdenes(){

        $motoristas = MotoristasOrdenes::orderBy('fecha_agarrada')->get();

        foreach ($motoristas as $mm){

            $infoMotorista = Motoristas::where('id', $mm->motoristas_id)->first();
            $infoOrdenes = Ordenes::where('id', $mm->ordenes_id)->first();

            $mm->nombre = $infoMotorista->nombre;
            $mm->usuario = $infoMotorista->usuario;

            $mm->fecha_agarrada = date("h:i A d-m-Y", strtotime($mm->fecha_agarrada));
            $mm->fecha_orden = date("h:i A d-m-Y", strtotime($infoOrdenes->fecha_orden));
            $mm->orden = $infoOrdenes->id;

            $estado = "En proceso";

            if($infoOrdenes->estado_5 == 1){
                $estado = "Orden Entregada";
            }

            if($infoOrdenes->estado_7 == 1){

                if($infoOrdenes->cancelado == 1){
                    $estado = "Orden Cancelada por: Cliente";
                }else{
                    $estado = "Orden Cancelada por: Propietario";
                }
            }

            $mm->estado = $estado;
        }

        return view('backend.admin.motoristas.ordenes.tablavistamotoristaordenes', compact('motoristas'));
    }

    public function informacionMotoristaOrden(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($info = MotoristasOrdenes::where('id', $request->id)->first()){

            $motoristas = Motoristas::where('activo', 1)->orderBy('nombre')->get();

            return ['success' => 1, 'idmoto' => $info->motoristas_id, 'motoristas' => $motoristas];
        }else{
            return ['success' => 2];
        }
    }

    public function editarMotoristaOrden(Request $request){
        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){ return ['success' => 0];}

        if(MotoristasOrdenes::where('id', $request->id)->first()){

            MotoristasOrdenes::where('id', $request->id)->update([
                'motoristas_id' => $request->idmoto,
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }

    }




}
