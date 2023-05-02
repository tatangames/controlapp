<?php

namespace App\Http\Controllers\Backend\Admin\Control;

use App\Http\Controllers\Controller;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisosController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $roles = Role::all()->pluck('name', 'id');

        return view('backend.admin.permisos.index', compact('roles'));
    }

    public function tablaUsuarios(){

        $usuarios = Usuarios::orderBy('id', 'ASC')->get();

        return view('backend.admin.permisos.tabla.tablapermisos', compact('usuarios'));
    }

    public function nuevoUsuario(Request $request){
        if(Usuarios::where('usuario', $request->usuario)->first()){
            return ['success' => 1];
        }

        $u = new Usuarios();
        $u->nombre = $request->nombre;
        $u->usuario = $request->usuario;
        $u->password = bcrypt($request->password);
        $u->activo = 1;

        if ($u->save()) {
            $u->assignRole($request->rol);
            return ['success' => 2];
        } else {
            return ['success' => 3];
        }
    }

    public function infoUsuario(Request $request){
        if($info = Usuarios::where('id', $request->id)->first()){

            $roles = Role::all()->pluck('name', 'id');

            $idrol = $info->roles->pluck('id');

            return ['success' => 1,
                'info' => $info,
                'roles' => $roles,
                'idrol' => $idrol];

        }else{
            return ['success' => 2];
        }
    }

    public function editarUsuario(Request $request){

        if(Usuarios::where('id', $request->id)->first()){

            if(Usuarios::where('usuario', $request->usuario)->where('id', '!=', $request->id)->first()){
                return ['success' => 1];
            }

            // usuario 1 no puede ser desactivado
            /*if($request->id == 1){
                if($request->toggle == 0){
                    return ['success' => 2];

            }*/

            $usuario = Usuarios::find($request->id);
            $usuario->nombre = $request->nombre;
            $usuario->usuario = $request->usuario;
            //$usuario->activo = $request->toggle;

            if($request->password != null){
                $usuario->password =  bcrypt($request->password);
            }

            //$usuario->assignRole($request->rol); asigna un rol extra

            //elimina el rol existente y agrega el nuevo
            $usuario->syncRoles($request->rol);

            $usuario->save();

            return ['success' => 3];
        }else{
            return ['success' => 4];
        }
    }

    public function nuevoRol(Request $request){

        $regla = array(
            'nombre' => 'required',
        );

        $mensaje = array(
            'nombre.required' => 'Nombre es requerido',
        );

        $validar = Validator::make($request->all(), $regla, $mensaje);

        if ($validar->fails()){return ['success' => 0];}

        // verificar si existe el rol
        if(Role::where('name', $request->nombre)->first()){
            return ['success' => 1];
        }

        Role::create(['name' => $request->nombre]);

        return ['success' => 2];
    }

    public function nuevoPermisoExtra(Request $request){

        // verificar si existe el permiso
        if(Permission::where('name', $request->nombre)->first()){
            return ['success' => 1];
        }

        Permission::create(['name' => $request->nombre, 'description' => $request->descripcion]);

        return ['success' => 2];
    }

    public function borrarPermisoGlobal(Request $request){

        // buscamos el permiso el cual queremos eliminar
        $permission = Permission::findById($request->idpermiso)->delete();

        return ['success' => 1];
    }
}
