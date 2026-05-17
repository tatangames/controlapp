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
use Illuminate\Support\Facades\Log;
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

            if (Hash::check($request->password, $info->password)) {

                // inicio sesion
                return ['success' => 1, 'id' => strval($info->id)];

            }else{
                // contraseña incorrecta
                return ['success' => 2];
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
