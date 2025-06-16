<?php

namespace App\Http\Controllers\backend\admin\Motorista;

use App\Http\Controllers\Controller;
use App\Models\MotoClienteDireccion;
use App\Models\MotoClienteDireccionMultiple;
use App\Models\Motoristas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MotoristaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function indexNuevoMotorista(){
        return view('backend.admin.motoristas.vistamotorista');
    }

    public function tablaNuevoMotorista(){
        $listado = Motoristas::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.motoristas.tablamotorista', compact('listado'));
    }


    public function nuevoMotorista(Request $request){

        $regla = array(
            'usuario' => 'required',
            'password' => 'required',
        );

        // nombre

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        $nuevo = new Motoristas();
        $nuevo->usuario = $request->usuario;
        $nuevo->password = bcrypt($request->password);
        $nuevo->nombre = $request->nombre;
        $nuevo->actualizar = 1;

        if($nuevo->save()){
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function infoMotorista(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        if($info = Motoristas::where('id', $request->id)->first()){

            return ['success' => 1, 'info' => $info];
        }else{
            return ['success' => 2];
        }
    }


    public function editarMotorista(Request $request){

        $rules = array(
            'id' => 'required',
            'usuario' => 'required'
        );

        // password, nombre

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if(Motoristas::where('id', $request->id)->first()){

            Motoristas::where('id', $request->id)->update([
                'nombre' => $request->nombre,
                'usuario' => $request->usuario,
            ]);

            if($request->password != null){
                Motoristas::where('id', $request->id)->update([
                    'password' => bcrypt($request->password)
                ]);
            }

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }




    //**********************************************************************************



    public function indexNuevoMotoristaDireccion(){
        return view('backend.admin.motoristas.clientedireccion.vistadireccioncliente');
    }

    public function tablaNuevoMotoristaDireccion(){
        $listado = MotoClienteDireccion::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.motoristas.clientedireccion.tabladireccioncliente', compact('listado'));
    }


    public function nuevoMotoristaDireccion(Request $request){

        $nuevo = new MotoClienteDireccion();
        $nuevo->nombre = $request->nombre;
        $nuevo->direccion = $request->direccion;
        $nuevo->referencia = $request->referencia;
        $nuevo->telefono = $request->telefono;
        $nuevo->latitud = $request->latitud;
        $nuevo->longitud = $request->longitud;

        if($nuevo->save()){

            // NOTIFICAR A TODOS LOS MOTORISTAS DEBEN ACTUALIZAR
            Motoristas::query()->update([
                'actualizar' => 1,
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function infoMotoristaDireccion(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        if($info = MotoClienteDireccion::where('id', $request->id)->first()){

            return ['success' => 1, 'info' => $info];
        }else{
            return ['success' => 2];
        }
    }


    public function editarMotoristaDireccion(Request $request){

        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if(MotoClienteDireccion::where('id', $request->id)->first()){

            MotoClienteDireccion::where('id', $request->id)->update([
                'nombre' => $request->nombre,
                'direccion' => $request->direccion,
                'referencia' => $request->referencia,
                'telefono' => $request->telefono,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
            ]);


            // NOTIFICAR A TODOS LOS MOTORISTAS DEBEN ACTUALIZAR
            Motoristas::query()->update([
                'actualizar' => 1,
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }


    public function borrarDireccion(Request $request)
    {
        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        MotoClienteDireccionMultiple::where('id_cliente_direc', $request->id)->delete();
        MotoClienteDireccion::where('id', $request->id)->delete();

        // NOTIFICAR A TODOS LOS MOTORISTAS DEBEN ACTUALIZAR
        Motoristas::query()->update([
            'actualizar' => 1,
        ]);

        return ['success' => 1];
    }


    public function verMapa($id){

        $googleapi = config('googleapi.Google_API');

        $info = MotoClienteDireccion::where('id', $id)->first();
        $latitud = $info->latitud;
        $longitud = $info->longitud;

        return view('backend.admin.motoristas.clientedireccion.mapadireccion', compact('latitud', 'longitud', 'googleapi'));
    }


    // *************************  EXTRA DIRECIONES ********************************

    public function indexNuevoMotoristaDireccionExtra($id)
    {
        return view('backend.admin.motoristas.clientedireccion.extra.vistaextradireccion', compact('id'));
    }


    public function tablaNuevoMotoristaDireccionExtra($id)
    {
        $listado = MotoClienteDireccionMultiple::where('id_cliente_direc', $id)
            ->orderBy('nombre', 'ASC')
            ->get();

        return view('backend.admin.motoristas.clientedireccion.extra.tablaextradireccion', compact('listado'));
    }


    public function borrarDireccionExtra(Request $request)
    {
        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }


        MotoClienteDireccionMultiple::where('id', $request->id)->delete();

        // NOTIFICAR A TODOS LOS MOTORISTAS DEBEN ACTUALIZAR
        Motoristas::query()->update([
            'actualizar' => 1,
        ]);

        return ['success' => 1];
    }



    public function nuevoMotoristaDireccionExtra(Request $request){

        Log::info($request->all());

        $nuevo = new MotoClienteDireccionMultiple();
        $nuevo->id_cliente_direc = $request->id;
        $nuevo->nombre = $request->nombre;
        $nuevo->direccion = $request->direccion;
        $nuevo->referencia = $request->referencia;
        $nuevo->telefono = $request->telefono;
        $nuevo->latitud = $request->latitud;
        $nuevo->longitud = $request->longitud;

        if($nuevo->save()){

            // NOTIFICAR A TODOS LOS MOTORISTAS DEBEN ACTUALIZAR
            Motoristas::query()->update([
                'actualizar' => 1,
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function infoMotoristaDireccionExtra(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        if($info = MotoClienteDireccionMultiple::where('id', $request->id)->first()){

            return ['success' => 1, 'info' => $info];
        }else{
            return ['success' => 2];
        }
    }


    public function editarMotoristaDireccionExtra(Request $request){

        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if(MotoClienteDireccionMultiple::where('id', $request->id)->first()){

            MotoClienteDireccionMultiple::where('id', $request->id)->update([
                'nombre' => $request->nombre,
                'direccion' => $request->direccion,
                'referencia' => $request->referencia,
                'telefono' => $request->telefono,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
            ]);

            // NOTIFICAR A TODOS LOS MOTORISTAS DEBEN ACTUALIZAR
            Motoristas::query()->update([
                'actualizar' => 1,
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }


    public function verMapaDireccionExtra($id){

        $googleapi = config('googleapi.Google_API');

        $poligono = MotoClienteDireccionMultiple::where('id', $id)->get();
        return view('backend.admin.motoristas.clientedireccion.extra.mapadireccionextra', compact('poligono', 'googleapi'));
    }




    //**********
    public function indexDireccionTodas(){
        return view('backend.admin.motoristas.clientedireccion.todas.vistatodasdirecciones');
    }


    public function tablaDireccionTodas(){

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

        return view('backend.admin.motoristas.clientedireccion.todas.tablatodasdirecciones', compact('resultsBloque'));
    }
}
