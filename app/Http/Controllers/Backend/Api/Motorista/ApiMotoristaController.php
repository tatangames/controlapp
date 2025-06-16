<?php

namespace App\Http\Controllers\backend\api\Motorista;

use App\Http\Controllers\Controller;
use App\Models\MotoClienteDireccion;
use App\Models\MotoClienteDireccionMultiple;
use App\Models\Motoristas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiMotoristaController extends Controller
{
    public function loginMotorista(Request $request){

        $rules = array(
            'usuario' => 'required',
            'password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules );

        if ($validator->fails()){
            return ['success' => 0];
        }

        if($info = Motoristas::where('usuario', $request->usuario)->first()){

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


    public function listadoDireccionesCliente(Request $request)
    {
        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules );

        if ($validator->fails()){
            return ['success' => 0];
        }

        $resultsBloque = [];

        $arrayDirecciones = MotoClienteDireccion::orderBy('nombre', 'ASC')->get();

        foreach ($arrayDirecciones as $direccion) {

            $direccion->idcliente = $direccion->id;

            $direccion->bloque = 1; // 👈 bloque 1 para principales
            $resultsBloque[] = $direccion;

            // Direcciones múltiples relacionadas
            $arrayMulti = MotoClienteDireccionMultiple::where('id_cliente_direc', $direccion->id)
                ->orderBy('nombre', 'ASC')
                ->get();

            foreach ($arrayMulti as $subdireccion) {
                $subdireccion->bloque = 2; // 👈 bloque 2 para múltiples
                $resultsBloque[] = $subdireccion;
                $subdireccion->idcliente = $subdireccion->id;
            }
        }

        // ACTUALIZAR MI USUARIO PARA NO MOSTRAR ACTUALIZAR DIRECCIONES
        Motoristas::where('id', $request->id)->update([
            'actualizar' => 0
        ]);

        return [ 'success' => 1, 'listado' => $resultsBloque];
    }


    public function VerificarDeboActualizar(Request $request)
    {
        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules );

        if ($validator->fails()){
            return ['success' => 0];
        }

        if($info = Motoristas::where('id', $request->id)->first()){

            // 0: NO DEBE ACTUALIZAR
            // 1: DEBE ACTUALIZAR

            return ['success' => 1, 'actualizar' => $info->actualizar];
        }else{
            return ['success' => 99];
        }
    }


    public function modificarGPS(Request $request)
    {

        Log::info("entraa");
        Log::info($request->all());

        $rules = array(
            'id' => 'required',
            'bloque' => 'required',
            'latitud' => 'required',
            'longitud' => 'required',
        );

        $validator = Validator::make($request->all(), $rules );

        if ($validator->fails()){
            return ['success' => 0];
        }

        DB::beginTransaction();

        try {

            if($request->bloque == 1){
                MotoClienteDireccion::where('id', $request->id)->update([
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                ]);
            }else{
                MotoClienteDireccionMultiple::where('id', $request->id)->update([
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                ]);
            }

            // NOTIFICAR A TODOS LOS MOTORISTAS DEBEN ACTUALIZAR
            Motoristas::query()->update([
                'actualizar' => 1,
            ]);

            DB::commit();
            return ['success' => 1];

            }catch(\Throwable $e){
                Log::info("error: " . $e);
                DB::rollback();
                return ['success' => 99];
        }
    }


}
