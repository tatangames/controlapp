<?php

namespace App\Http\Controllers\Backend\Api\Cliente;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJobs;
use App\Mail\SendEmailCodigo;
use App\Models\Clientes;
use App\Models\InformacionAdmin;
use App\Models\IntentosCorreo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ApiClienteController extends Controller
{
    public function loginCliente(Request $request){


        $rules = array(
            'usuario' => 'required',
            'password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules );

        if ( $validator->fails()){
            return ['success' => 0];
        }

        if($info = Clientes::where('usuario', $request->usuario)->first()){

            if($info->activo == 0){

                $mensaje = "Usuario ha sido bloqueado. Contactar a la administración";
                return ['success' => 1, 'msj1' => $mensaje];
            }

            if (Hash::check($request->password, $info->password)) {

                if($request->token_fcm != null){
                    Clientes::where('id', $info->id)->update(['token_fcm' => $request->token_fcm]);
                }

                // inicio sesion
                return ['success' => 2, 'id' => strval($info->id)];

            }else{
                // contraseña incorrecta
                return ['success' => 3];
            }

        } else {
            // usuario no encontrado
            return ['success' => 4];
        }
    }



    public function loginClienteV2(Request $request){

        $rules = array(
            'usuario' => 'required',
            'password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules );

        if ($validator->fails()){
            return ['success' => 0];
        }

        if($info = Clientes::where('usuario', $request->usuario)->first()){

            if (Hash::check($request->password, $info->password)) {

                // inicio sesion
                return ['success' => 1, 'id' => strval($info->id)];

            }else{
                // contraseña incorrecta (datos incorrectos)
                return ['success' => 2];
            }

        } else {
            // usuario no encontrado (datos incorrectos)
            return ['success' => 2];
        }
    }



    public function enviarCodigoCorreo(Request $request){
        $rules = array(
            'correo' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){return ['success' => 0]; }

        if($info = Clientes::where('correo', $request->correo)->first()){

            // codigo aleaotorio
            $codigo = '';
            for($i = 0; $i < 6; $i++) {
                $codigo .= mt_rand(0, 9);
            }

            Clientes::where('id', $info->id)->update(['codigo_correo' => $codigo]);

            $fecha = Carbon::now('America/El_Salvador');

            // intentos de cuando intento recuperar contraseña
            $dato = new IntentosCorreo();
            $dato->correo = $request->correo;
            $dato->fecha = $fecha;
            $dato->save();

           // $correo = new SendEmailCodigo($codigo);
           // Mail::to($request->correo)->send($correo);

            // envio de correo
            SendEmailJobs::dispatch($codigo, $request->correo);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function verificarCodigoCorreoPassword(Request $request)
    {
        $rules = array(
            'codigo' => 'required',
            'correo' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['success' => 0];
        }

        // verificar codigo
        if ($info = Clientes::where('correo', $request->correo)
            ->where('codigo_correo', $request->codigo)
            ->first()) {

            // puede cambiar contraseña
            return ['success' => 1, 'id' => $info->id];
        } else {
            // codigo incorrecto
            return ['success' => 2];
        }
    }

    public function actualizarPasswordCliente(Request $request){

        $rules = array(
            'id' => 'required',
            'password' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if(Clientes::where('id', $request->id)->first()){

            Clientes::where('id', $request->id)->update(['password' => Hash::make($request->password)]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

}
