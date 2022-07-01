<?php

namespace App\Http\Controllers\Backend\Api\Afiliados;

use App\Http\Controllers\Controller;
use App\Models\Afiliados;
use App\Models\Horario;
use App\Models\InformacionAdmin;
use App\Models\Ordenes;
use App\Models\OrdenesDirecciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAfiliadosController extends Controller
{
    public function loginAfiliado(Request $request){

        $rules = array(
            'usuario' => 'required',
            'password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if($p = Afiliados::where('usuario', $request->usuario)->first()){

            if($p->activo == 0){
                // propietario inactivo
                return ['success' => 1];
            }

            if (Hash::check($request->password, $p->password)) {

                if($request->tokenfcm != null){
                    Afiliados::where('id', $p->id)->update(['token_fcm' => $request->tokenfcm]);
                }

                // setear dedisponibilidad
                Afiliados::where('id', $p->id)->update(['disponible' => 1]);

                return ['success' => 2, 'id' => $p->id];
            }else{
                // contraseña incorrecta
                return ['success' => 3];
            }
        }else{
            // usuario no encontrado
            return ['success' => 4];
        }
    }


    public function nuevasOrdenes(Request $request){

        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){return ['success' => 0]; }

        if($p = Afiliados::where('id', $request->id)->first()){

            if($p->activo == 0){
                return ['success'=> 1];
            }

            $orden = Ordenes::where('visible_p', 1)->get();

            foreach($orden as $o){

                $infoOrdenesDireccion = OrdenesDirecciones::where('ordenes_id', $o->id)->first();

                $o->fecha_orden = date("h:i A d-m-Y", strtotime($o->fecha_orden));
                $o->precio_consumido = number_format((float)$o->precio_consumido, 2, '.', ',');

                $o->cliente = $infoOrdenesDireccion->nombre;
                $o->telefono = $infoOrdenesDireccion->telefono;

                // verificar metodo entrega.
                //domicilio
                if($o->tipoentrega == 1){
                    $o->direccion = $infoOrdenesDireccion->direccion;
                }else{
                    $o->direccion = "";
                }

                if($o->tipoentrega == 1){
                    $entrega = "A Domicilio";
                }else{
                    $entrega = "Pasar a Traer a Local";
                }

                $o->entrega = $entrega;
            }

            return ['success' => 2, 'ordenes' => $orden];
        }else{
            return ['success' => 3];
        }
    }

    public function informacionCuenta(Request $request){

        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if($p = Afiliados::where('id', $request->id)->first()){

            return ['success'=> 1, 'nombre' => $p->nombre];
        }else{
            return ['success'=> 2];
        }
    }

    public function informacionDisponibilidad(Request $request){

        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if($p = Afiliados::where('id', $request->id)->first()){

            return ['success'=> 1, 'disponible' => $p->disponible];
        }else{
            return ['success'=> 2];
        }
    }

    public function guardarEstados(Request $request){

        $rules = array(
            'id' => 'required',
            'disponibilidad' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if(Afiliados::where('id', $request->id)->first()){

            Afiliados::where('id', $request->id)->update(['disponible' => $request->disponibilidad]);

            return ['success'=> 1];
        }else{
            return ['success'=> 2];
        }
    }

    public function actualizarPasswordAfiliado(Request $request){

        $rules = array(
            'id' => 'required',
            'password' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if(Afiliados::where('id', $request->id)->first()){

            Afiliados::where('id', $request->id)->update(['password' => Hash::make($request->password)]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function listadoHorarios(Request $request){

        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if(Afiliados::where('id', $request->id)->first()){

            $horario = Horario::orderBy('id')->get();

            foreach($horario as $s){
                $s->hora1 = date("h:i A", strtotime($s->hora1));
                $s->hora2 = date("h:i A", strtotime($s->hora2));
            }

            return ['success' => 1, 'horario' => $horario];

        }else{
            return ['success'=> 2];
        }
    }

    public function informacionCerrado(Request $request){

        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if(Afiliados::where('id', $request->id)->first()){

            $info = InformacionAdmin::where('id', 1)->first();

            return ['success'=> 1, 'disponible' => $info->cerrado, 'mensaje' => $info->mensaje_cerrado];
        }else{
            return ['success'=> 2];
        }
    }

    public function guardarEstadosCerrado(Request $request){

        $rules = array(
            'id' => 'required',
            'disponibilidad' => 'required',
            'mensaje' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if(Afiliados::where('id', $request->id)->first()){

            InformacionAdmin::where('id', 1)->update(['cerrado' => $request->disponibilidad,
                'mensaje_cerrado' => $request->mensaje]);

            return ['success'=> 1];
        }else{
            return ['success'=> 2];
        }
    }
}
