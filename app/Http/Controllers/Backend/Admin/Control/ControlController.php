<?php

namespace App\Http\Controllers\Backend\Admin\Control;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControlController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function indexRedireccionamiento(){

        $user = Auth::user();

        // $permiso = $user->getAllPermissions()->pluck('name');

        // Rol: Super-Admin
       /* if($user->hasPermissionTo('seccion.estadisticas')){
            $ruta = 'index.estadisticas';
        }

        // Rol: Revisador
        else  if($user->hasPermissionTo('seccion.estadisticas')){
            $ruta = 'index.estadisticas';
        }

        else{
            // no tiene ningun permiso de vista, redirigir a pantalla sin permisos
            $ruta = 'no.permisos.index';
        }*/

        $ruta = 'index.ordenes.pendientes';

        return view('backend.index', compact('user', 'ruta'));
    }

    public function indexSinPermiso(){
        return view('errors.403');
    }
}
