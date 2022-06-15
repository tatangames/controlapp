<?php

namespace App\Http\Controllers\Backend\Admin\Mapa;

use App\Http\Controllers\Controller;
use App\Models\ZonaPoligono;
use App\Models\Zonas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZonaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('backend.admin.zonas.index');
    }

    // tabla para ver zonas
    public function tablaZonas(){
        $zonas = Zonas::orderBy('id', 'ASC')->get();

        foreach($zonas as $z){
            $z->hora_abierto_delivery = date("h:i A", strtotime($z->hora_abierto_delivery));
            $z->hora_cerrado_delivery = date("h:i A", strtotime($z->hora_cerrado_delivery));
        }

        return view('backend.admin.zonas.tabla.tablazonas', compact('zonas'));
    }

    // crear zona
    public function nuevaZona(Request $request){

        $rules = array(
            'nombre' => 'required',
            'horaabierto' => 'required',
            'horacerrado' => 'required',
            'latitud' => 'required',
            'longitud' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){return ['success' => 0];}

        $zona = new Zonas();
        $zona->nombre = $request->nombre;
        $zona->latitud = $request->latitud;
        $zona->longitud = $request->longitud;
        $zona->saturacion = 0;
        $zona->hora_abierto_delivery = $request->horaabierto;
        $zona->hora_cerrado_delivery = $request->horacerrado;
        $zona->activo = 1;
        $zona->mensaje_bloqueo = null;
        $zona->minimo_consumo = $request->precio;

        if($zona->save()){
            return ['success'=>1];
        }else{
            return ['success'=>2];
        }
    }

    // informacion de la zona
    public function informacionZona(Request $request){
        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){return ['success' => 0];}

        if($zona = Zonas::where('id', $request->id)->first()){
            return['success' => 1, 'zona' => $zona];
        }else{
            return['success' => 2];
        }

    }

    // editar la zona
    public function editarZona(Request $request){
        $rules = array(
            'id' => 'required',
            'nombre' => 'required',
            'horaabierto' => 'required',
            'horacerrado' => 'required',
            'togglep' => 'required',
            'togglea' => 'required',
            'latitud' => 'required',
            'longitud' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){return ['success' => 0];}

        if(Zonas::where('id', $request->id)->first()){

            Zonas::where('id', $request->id)->update([
                'nombre' => $request->nombre,
                'hora_abierto_delivery' => $request->horaabierto,
                'hora_cerrado_delivery' => $request->horacerrado,
                'saturacion' => $request->togglep,
                'activo' => $request->togglea,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'mensaje_bloqueo' => $request->mensaje,
                'minimo_consumo' => $request->precio]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }

    }

    public function indexPoligono($id){
        $nombre = Zonas::where('id', $id)->pluck('nombre')->first();
        return view('backend.admin.zonas.poligono.index', compact('nombre', 'id'));
    }

    public function nuevoPoligono(Request $request){

        $regla = array(
            'id' => 'required',
            'latitud' => 'required|array',
            'longitud' => 'required|array',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0];}

        for ($i = 0; $i < count($request->latitud); $i++) {

            $ingreso = new ZonaPoligono();
            $ingreso->zonas_id = $request->id;
            $ingreso->latitud = $request->latitud[$i];
            $ingreso->longitud = $request->longitud[$i];
            $ingreso->save();
        }

        return ['success' => 1];
    }

    public function borrarPoligonos(Request $request){

        $rules = array(
            'id' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){ return ['success' => 0]; }

        ZonaPoligono::where('zonas_id', $request->id)->delete();

        return ['success'=> 1];
    }

    public function verMapa($id){

        $googleapi = config('googleapi.Google_API');

        $poligono = ZonaPoligono::where('zonas_id', $id)->get();
        return view('backend.admin.zonas.mapa.index', compact('poligono', 'googleapi'));
    }

}
