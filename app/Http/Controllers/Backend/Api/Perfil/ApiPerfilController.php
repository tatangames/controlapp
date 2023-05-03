<?php

namespace App\Http\Controllers\Backend\Api\Perfil;

use App\Http\Controllers\Controller;
use App\Models\CarritoExtra;
use App\Models\CarritoTemporal;
use App\Models\Clientes;
use App\Models\DireccionCliente;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiPerfilController extends Controller
{
    public function informacionPerfil(Request $request){

        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){return ['success' => 0]; }

        if($info = Clientes::where('id', $request->id)->first()){

            return ['success' => 1, 'correo' => $info->correo,
                'imagen' => $info->imagen,
                'usuario' => $info->usuario];
        }else{
            return ['success' => 2];
        }
    }

    public function editarPerfil(Request $request){
        $reglaDatos = array(
            'id' => 'required',
            'correo' => 'required'
        );

        $validator = Validator::make($request->all(), $reglaDatos);

        if ( $validator->fails()){return ['success' => 0]; }

        if(Clientes::where('correo', $request->correo)
            ->where('id', '!=', $request->id)
            ->first()){

            // correo ya esta registrado
            return ['success' => 1];
        }

        Clientes::where('id', $request->id)->update([
            'correo' => $request->correo]);

        return ['success' => 2];
    }

    public function listadoDeDirecciones(Request $request){
        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){return ['success' => 0]; }

        if(Clientes::where('id', $request->id)->first()){

            $direccion = DireccionCliente::where('clientes_id', $request->id)->get();

            return ['success' => 1, 'direcciones' => $direccion];
        }else{
            return ['succcess'=> 2];
        }
    }

    public function seleccionarDireccion(Request $request){

        $reglaDatos = array(
            'dirid' => 'required',
            'id' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if ( $validarDatos->fails()){return ['success' => 0]; }

        if(Clientes::where('id', $request->id)->first()){

            if(DireccionCliente::where('clientes_id', $request->id)
                ->where('id', $request->dirid)->first()){

                DB::beginTransaction();

                try {

                    // setear a 0
                    DireccionCliente::where('clientes_id', $request->id)->update(['seleccionado' => 0]);

                    // setear a 1 el id de la direccion que envia el usuario
                    DireccionCliente::where('id', $request->dirid)->update(['seleccionado' => 1]);

                    if($tabla1 = CarritoTemporal::where('clientes_id', $request->id)->first()){
                        CarritoExtra::where('carrito_temporal_id', $tabla1->id)->delete();
                        CarritoTemporal::where('clientes_id', $request->id)->delete();
                    }

                    DB::commit();

                    // direccion seleccionda
                    return ['success' => 1];

                }catch(\Throwable $e){
                    DB::rollback();
                    // error
                    return ['success' => 0];
                }

            }else{
                // cliente no encontrado
                return ['success' => 2];
            }
        }else{
            return ['success' => 2];
        }
    }

    public function eliminarDireccion(Request $request){

        $reglaDatos = array(
            'id' => 'required',
            'dirid' => 'required',
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if ( $validarDatos->fails()){return ['success' => 0]; }

        if($infoDire = DireccionCliente::where('id', $request->dirid)
            ->where('clientes_id', $request->id)->first()){

            DB::beginTransaction();

            try {

                $total = DireccionCliente::where('clientes_id', $request->id)->count();

                if($total > 1){

                    // verificar si esta direccion era la que estaba seleccionada, para poner una aleatoria
                    $info = DireccionCliente::where('id', $infoDire->id)->first();

                    // borrar direccion
                    DireccionCliente::where('id', $infoDire->id)->delete();

                    // si era la seleccionada poner aleatoria, sino no hacer nada
                    if($info->seleccionado == 1){

                        // volver a buscar la primera linea y poner seleccionado
                        $datos = DireccionCliente::where('clientes_id', $request->id)->first();
                        DireccionCliente::where('id', $datos->id)->update(['seleccionado' => 1]);
                    }

                    DB::commit();

                    return ['success' => 1];
                }else{
                    // no puede borrar la direccion
                    return ['success' => 2];
                }
            }catch(\Throwable $e){
                DB::rollback();
                return ['success' => 3];
            }
        }else{
            return ['success' => 3];
        }
    }


    public function puntosZonaPoligonos(){

        $rr = DB::table('zonas AS z')
            ->join('zona_poligono AS p', 'p.zonas_id', '=', 'z.id')
            ->select('z.id')
            ->where('z.activo', 1)
            ->groupBy('id')
            ->get();

        // meter zonas que si tienen poligonos
        $pila = array();
        foreach($rr as $p){
            array_push($pila, $p->id);
        }

        $tablas = DB::table('zonas')
            ->select('id AS idZona', 'nombre AS nombreZona')
            ->whereIn('id', $pila)
            ->get();

        $resultsBloque = array();
        $index = 0;

        foreach($tablas  as $secciones){
            array_push($resultsBloque,$secciones);

            $subSecciones = DB::table('zona_poligono AS pol')
                ->select('pol.latitud AS latitudPoligono', 'pol.longitud AS longitudPoligono')
                ->where('pol.zonas_id', $secciones->idZona)
                ->get();

            $resultsBloque[$index]->poligonos = $subSecciones;
            $index++;
        }

        return [
            'success' => 1,
            'poligono' => $tablas
        ];
    }

    public function nuevaDireccionCliente(Request $request){
        $reglaDatos = array(
            'id' => 'required',
            'nombre' => 'required',
            'direccion' => 'required',
            'zona_id' => 'required',
            'telefono' => 'required'
        );

        $validarDatos = Validator::make($request->all(), $reglaDatos);

        if ( $validarDatos->fails()){return ['success' => 0]; }

        if(Clientes::where('id', $request->id)->first()){

            DB::beginTransaction();

            try {

                $di = new DireccionCliente();
                $di->zonas_id = $request->zona_id;
                $di->clientes_id = $request->id;
                $di->nombre = $request->nombre;
                $di->direccion = $request->direccion;
                $di->punto_referencia = $request->punto_referencia;
                $di->seleccionado = 1;
                $di->latitud = $request->latitud;
                $di->longitud = $request->longitud;
                $di->latitudreal = $request->latitudreal;
                $di->longitudreal = $request->longitudreal;
                $di->telefono = $request->telefono;

                if($di->save()){

                    try {
                        DireccionCliente::where('clientes_id', $request->id)
                            ->where('id', '!=', $di->id)
                            ->update(['seleccionado' => 0]);

                        DB::commit();

                        return ['success' => 1];

                    }  catch (\Exception $ex) {
                        DB::rollback();

                        return ['success' => 2]; // error
                    }
                }else{
                    return ['success' => 2]; // error
                }

            } catch(\Throwable $e){
                DB::rollback();
                return ['success' => 2];
            }
        }else{
            return ['success' => 2];
        }
    }

    public function cambiarPasswordPerfil(Request $request){

        $rules = array(
            'id' => 'required',
            'password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){return ['success' => 0]; }

        if(Clientes::where('id', $request->id)->first()){
            Clientes::where('id', $request->id)->update(['password' => Hash::make($request->password)]);
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function listaHorarios(){

        $horario = Horario::orderBy('id')->get();

        foreach($horario as $s){
            $s->hora1 = date("h:i A", strtotime($s->hora1));
            $s->hora2 = date("h:i A", strtotime($s->hora2));
        }

        return ['success' => 1, 'horario' => $horario];
    }


    // opcion elegida si borrar o no carrito de compras
    public function opcionCarritoCompras(Request $request){

        $rules = array(
            'id' => 'required', // id cliente
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){return ['success' => 0]; }

        if($info = Clientes::where('id', $request->id)->first()){

            return ['success' => 1, 'opcion' => $info->borrar_carrito];
        }else{
            return ['success' => 2];
        }
    }

    public function guardarOpcionCarritoCompras(Request $request){

        $rules = array(
            'id' => 'required', // id cliente
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){return ['success' => 0]; }

        if(Clientes::where('id', $request->id)->first()){


            Clientes::where('id', $request->id)->update([
                'borrar_carrito' => $request->disponible]);


            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }




}
