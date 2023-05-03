<?php

namespace App\Http\Controllers\Backend\Admin\Horario;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\InformacionAdmin;
use App\Models\IntentosCorreo;
use App\Models\TipoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class HorarioController extends Controller
{

    public function indexHorario(){
        return view('backend.admin.zonas.horario.vistahorario');
    }

    // tabla
    public function tablaHorario(){
        $horario = Horario::orderBy('dia', 'ASC')->get();

        foreach ($horario as $hh){
            $hh->hora1 = date("h:i A", strtotime($hh->hora1));
            $hh->hora2 = date("h:i A", strtotime($hh->hora2));
        }

        return view('backend.admin.zonas.horario.tablahorario', compact('horario'));
    }

    public function informacionHorario(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        if($horario = Horario::where('id', $request->id)->first()){

            return ['success' => 1, 'horario' => $horario];
        }else{
            return ['success' => 2];
        }
    }


    public function editarHorario(Request $request){
        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){ return ['success' => 0];}

        if(Horario::where('id', $request->id)->first()){

            Horario::where('id', $request->id)->update([
                'hora1' => $request->hora1,
                'hora2' => $request->hora2,
                'cerrado' => $request->cbcerrado
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function indexConfiguracion(){
        return view('backend.admin.informacion.vistainformacion');
    }

    // tabla
    public function tablaConfiguracion(){
        $info = InformacionAdmin::get();

        return view('backend.admin.informacion.tablainformacion', compact('info'));
    }

    public function informacionConfiguracion(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        if($info = InformacionAdmin::where('id', $request->id)->first()){

            return ['success' => 1, 'info' => $info];
        }else{
            return ['success' => 2];
        }
    }


    public function editarConfiguracion(Request $request){
        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){ return ['success' => 0];}

        if(InformacionAdmin::where('id', $request->id)->first()){

            InformacionAdmin::where('id', $request->id)->update([
                'mensaje_cerrado' => $request->mensaje,
                'cerrado' => $request->cbcerrado,
                'activo_slider' => $request->cbcarrusel
            ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function indexIntentosCorreo(){
        return view('backend.admin.intentos.vistaintentocorreo');

    }

    public function tablaIntentosCorreo(){
        $correos = IntentosCorreo::orderBy('fecha')->get();

        foreach ($correos as $cc){
            $cc->fecha = date("d-m-Y h:i A", strtotime($cc->fecha));
        }

        return view('backend.admin.intentos.tablaintentocorreo', compact('correos'));
    }



    // ******** TIPO DE SERVICIO /****************

    public function indexTipoServicio(){
        return view('backend.admin.tipo.vistatipos');
    }

    public function tablaTipoServicio(){

        $lista = TipoServicio::get();

        return view('backend.admin.tipo.tablatipos', compact('lista'));

    }

    public function nuevoTipoServicio(Request $request){

        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        $ca = new TipoServicio();
        $ca->nombre = $request->nombre;
        if($ca->save()){
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function informacionTipoServicio(Request $request){


        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        if($bloque = TipoServicio::where('id', $request->id)->first()){

            return ['success' => 1, 'tipo' => $bloque];
        }else{
            return ['success' => 2];
        }
    }


    public function editarTipoServicio(Request $request){

        $rules = array(
            'id' => 'required',
            'nombre' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if(TipoServicio::where('id', $request->id)->first()){

            TipoServicio::where('id', $request->id)->update([
                'nombre' => $request->nombre,
            ]);
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }




}
