<?php

namespace App\Http\Controllers\Backend\Admin\Servicios;

use App\Http\Controllers\Controller;
use App\Models\BloqueServicios;
use App\Models\BloqueSlider;
use App\Models\Categorias;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CategoriasController extends Controller
{
    public function indexBloque(){
        return view('backend.admin.bloques.vistabloques');
    }

    // tabla
    public function tablaBloque(){
        $bloques = BloqueServicios::orderBy('posicion')->get();

        return view('backend.admin.bloques.tablabloques', compact('bloques'));
    }

    public function nuevoBloque(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'imagen' => 'required',
        ]);

        // Nombre único para la imagen
        $nombreFoto = Str::random(15) . '_' . microtime(true) . '.' .
            strtolower($request->imagen->getClientOriginalExtension());

        // Subir imagen
        $subido = Storage::disk('imagenes')->put($nombreFoto, File::get($request->imagen));

        if (!$subido) {
            return ['success' => 2];
        }

        // Siguiente posición
        $posicion = (BloqueServicios::max('posicion') ?? 0) + 1;

        // Crear registro
        BloqueServicios::create([
            'nombre'   => $request->nombre,
            'imagen'   => $nombreFoto,
            'activo'   => 1,
            'posicion' => $posicion,
        ]);

        return ['success' => 1];
    }

    public function informacionBloque(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        if($bloque = BloqueServicios::where('id', $request->id)->first()){

            return ['success' => 1, 'bloque' => $bloque];
        }else{
            return ['success' => 2];
        }
    }

    public function editarBloque(Request $request)
    {
        $request->validate([
            'id'     => 'required',
            'nombre' => 'required',
            'imagen' => 'nullable',
        ]);

        $bloque = BloqueServicios::findOrFail($request->id);

        $datos = [
            'nombre' => $request->nombre,
            'activo' => $request->cbactivo,
        ];

        if ($request->hasFile('imagen')) {
            $nombreFoto = Str::random(15) . '_' . microtime(true) . '.' .
                strtolower($request->imagen->getClientOriginalExtension());

            $subido = Storage::disk('imagenes')->put($nombreFoto, file_get_contents($request->imagen));

            if (!$subido) {
                return ['success' => 2];
            }

            // Eliminar imagen anterior
            if (Storage::disk('imagenes')->exists($bloque->imagen)) {
                Storage::disk('imagenes')->delete($bloque->imagen);
            }

            $datos['imagen'] = $nombreFoto;
        }

        $bloque->update($datos);

        return ['success' => 1];
    }

    public function ordenarBloque(Request $request){

        $tasks = BloqueServicios::all();

        foreach ($tasks as $task) {
            $id = $task->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $task->update(['posicion' => $order['posicion']]);
                }
            }
        }
        return ['success' => 1];
    }

    // *** CATEGORIAS *** //

    public function indexCategorias($id){
        return view('backend.admin.categorias.vistacategorias', compact('id'));
    }

    // tabla
    public function tablaCategorias($id){
        $categorias = Categorias::where('id_bloque_servicios', $id)->orderBy('posicion')->get();

        return view('backend.admin.categorias.tablacategorias', compact('categorias'));
    }

    public function nuevaCategorias(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'id'     => 'required',
        ]);

        $posicion = (Categorias::where('id_bloque_servicios', $request->id)->max('posicion') ?? 0) + 1;

        Categorias::create([
            'id_bloque_servicios' => $request->id,
            'nombre'              => $request->nombre,
            'activo'              => 1,
            'posicion'            => $posicion,
        ]);

        return ['success' => 1];
    }

    public function informacionCategorias(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        if($categoria = Categorias::where('id', $request->id)->first()){

            return ['success' => 1, 'categoria' => $categoria];
        }else{
            return ['success' => 2];
        }
    }

    public function editarCategorias(Request $request){

        $rules = array(
            'id' => 'required',
            'nombre' => 'required'
        );

        // cbactivo

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if(Categorias::where('id', $request->id)->first()){

            Categorias::where('id', $request->id)->update([
                'nombre' => $request->nombre,
                'activo' => $request->cbactivo,
            ]);
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }


    public function ordenarCategorias(Request $request){

        $tasks = Categorias::all();

        foreach ($tasks as $task) {
            $id = $task->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $task->update(['posicion' => $order['posicion']]);
                }
            }
        }
        return ['success' => 1];
    }


    // *** PRODUCTOS *** //

    public function indexProductos($id){

        $categoria = Categorias::where('id', $id)->pluck('nombre')->first();

        return view('backend.admin.productos.vistaproductos', compact('id', 'categoria'));
    }

    // tabla
    public function tablaProductos($id){

        $productos = Producto::where('id_categorias', $id)->orderBy('posicion')->get();

        foreach ($productos as $pp){
            $pp->precio = number_format((float)$pp->precio, 2, '.', ',');
        }

        return view('backend.admin.productos.tablaproductos', compact('productos'));
    }

    public function nuevoProducto(Request $request)
    {
        $request->validate([
            'nombre'      => 'required',
            'idcategoria' => 'required',
        ]);

        // imagen, precio

        $nombreFoto = null;

        if ($request->hasFile('imagen')) {
            $nombreFoto = Str::random(15) . '_' . microtime(true) . '.' .
                strtolower($request->imagen->getClientOriginalExtension());

            $subido = Storage::disk('imagenes')->put($nombreFoto, file_get_contents($request->imagen));

            if (!$subido) {
                return ['success' => 2];
            }
        }

        $posicion = (Producto::where('id_categorias', $request->idcategoria)->max('posicion') ?? 0) + 1;

        Producto::create([
            'id_categorias'  => $request->idcategoria,
            'nombre'         => $request->nombre,
            'imagen'         => $nombreFoto,
            'descripcion'    => $request->descripcion,
            'precio'         => $request->precio,
            'activo'         => 1,
            'posicion'       => $posicion,
            'utiliza_nota'   => $request->cbnota,
            'nota'           => $request->nota,
            'utiliza_imagen' => $nombreFoto ? $request->cbimagen : 0,
        ]);

        return ['success' => 1];
    }

    public function informacionProductos(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        if($info = Producto::where('id', $request->id)->first()){

            $cate = Categorias::orderBy('nombre')->get();

            return ['success' => 1, 'producto' => $info, 'cate' => $cate];
        }else{
            return ['success' => 2];
        }
    }



    public function editarProductos(Request $request)
    {
        $request->validate([
            'id'     => 'required',
            'nombre' => 'required',
        ]);

        // imagen, precio

        $producto = Producto::findOrFail($request->id);

        // Validar: quiere usar imagen pero no tiene ninguna
        if (!$request->hasFile('imagen') && $producto->imagen === null && $request->cbimagen == 1) {
            return ['success' => 3];
        }

        $nombreFoto = null;

        if ($request->hasFile('imagen')) {
            $nombreFoto = Str::random(15) . '_' . microtime(true) . '.' .
                strtolower($request->imagen->getClientOriginalExtension());

            $subido = Storage::disk('imagenes')->put($nombreFoto, file_get_contents($request->imagen));

            if (!$subido) {
                return ['success' => 2];
            }

            // Eliminar imagen anterior
            if ($producto->imagen && Storage::disk('imagenes')->exists($producto->imagen)) {
                Storage::disk('imagenes')->delete($producto->imagen);
            }
        }

        $producto->update([
            'nombre'         => $request->nombre,
            'descripcion'    => $request->descripcion,
            'precio'         => $request->precio,
            'activo'         => $request->cbactivo,
            'utiliza_nota'   => $request->cbnota,
            'nota'           => $request->nota,
            'utiliza_imagen' => $nombreFoto ? $request->cbimagen : ($producto->imagen ? $request->cbimagen : 0),
            'imagen'         => $nombreFoto ?? $producto->imagen,
        ]);

        return ['success' => 1];
    }




    public function ordenarProductos(Request $request){

        $tasks = Producto::all();

        foreach ($tasks as $task) {
            $id = $task->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $task->update(['posicion' => $order['posicion']]);
                }
            }
        }
        return ['success' => 1];
    }



    // ---- SLIDERS ----


}
