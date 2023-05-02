<?php

namespace App\Http\Controllers\Backend\Api\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Clientes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiRegistroController extends Controller
{
    public function registroCliente(Request $request){

        $rules = array(
            'usuario' => 'required',
            'password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        // verificar si existe el usuario
        if(Clientes::where('usuario', $request->usuario)->first()){
            return ['success' => 1];
        }

        if($request->correo != null) {
            // verificar si existe el correo
            if (Clientes::where('correo', $request->correo)->first()) {
                return ['success' => 2];
            }
        }

        $fecha = Carbon::now('America/El_Salvador');

        $usuario = new Clientes();
        $usuario->usuario = $request->usuario;
        $usuario->correo = $request->correo;
        $usuario->codigo_correo = null;
        $usuario->password = Hash::make($request->password);
        $usuario->fecha = $fecha;
        $usuario->activo = 1;
        $usuario->token_fcm = $request->token_fcm;
        $usuario->borrar_carrito = 0;

        if($usuario->save()){

            return ['success'=> 3, 'id'=> strval($usuario->id)];

        }else{
            return ['success' => 4];
        }
    }
}
