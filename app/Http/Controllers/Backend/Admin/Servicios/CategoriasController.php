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

        $posicion = (Categorias::max('posicion') ?? 0) + 1;

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
    public function indexSliders(){

        $productos = Producto::where('activo', 1)->orderBy('nombre')->get();

        return view('backend.admin.slider.vistaSlider', compact('productos'));
    }

    public function tablaSliders(){
        $slider = BloqueSlider::orderBy('posicion')->get();

        foreach ($slider as $ss){

            if($info = Producto::where('id', $ss->id_producto)->first()){
                $ss->producto = $info->nombre;
            }
        }

        return view('backend.admin.slider.tablaSlider', compact('slider'));
    }

    public function nuevoSliders(Request $request){

        if($request->file('imagen')){

            $cadena = Str::random(15);
            $tiempo = microtime();
            $union = $cadena.$tiempo;
            $nombre = str_replace(' ', '_', $union);

            $extension = '.'.$request->imagen->getClientOriginalExtension();
            $nombreFoto = $nombre.strtolower($extension);
            $avatar = $request->file('imagen');
            $upload = Storage::disk('imagenes')->put($nombreFoto, \File::get($avatar));

            if($upload){

                if($info = BloqueSlider::orderBy('posicion', 'DESC')->first()){
                    $suma = $info->posicion + 1;
                }else{
                    $suma = 1;
                }

                $ca = new BloqueSlider();
                $ca->descripcion = $request->nombre;
                $ca->imagen = $nombreFoto;
                $ca->posicion = $suma;
                $ca->id_producto = $request->producto;
                $ca->redireccionamiento = $request->toredireccion;



                if($ca->save()){
                    return ['success' => 1];
                }else{
                    return ['success' => 2];
                }
            }else{
                return ['success' => 2];
            }

        }else {
            return ['success' => 2];
        }
    }

    public function ordenarSliders(Request $request){
        $tasks = BloqueSlider::all();

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

    public function borrarSliders(Request $request){
        $rules = array(
            'id' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){return ['success' => 0]; }

        if($info = BloqueSlider::where('id', $request->id)->first()){

            if(Storage::disk('imagenes')->exists($info->imagen)){
                Storage::disk('imagenes')->delete($info->imagen);
            }

            BloqueSlider::where('id', $request->id)->delete();
            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }

    public function informacionSlider(Request $request){

        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        if($bloque = BloqueSlider::where('id', $request->id)->first()){

            $producto = Producto::where('activo', 1)->orderBy('nombre')->get();

            return ['success' => 1, 'slider' => $bloque, 'producto' => $producto,
                'idproducto' => $bloque->id_producto];
        }else{
            return ['success' => 2];
        }
    }

    public function editarSlider(Request $request){

        Log::info($request->all());

        if($info = BloqueSlider::where('id', $request->id)->first()){

            if($request->hasFile('imagen')){

                $cadena = Str::random(15);
                $tiempo = microtime();
                $union = $cadena.$tiempo;
                $nombre = str_replace(' ', '_', $union);

                $extension = '.'.$request->imagen->getClientOriginalExtension();
                $nombreFoto = $nombre.strtolower($extension);
                $avatar = $request->file('imagen');
                $upload = Storage::disk('imagenes')->put($nombreFoto, \File::get($avatar));

                if($upload){
                    $imagenOld = $info->imagen;

                    BloqueSlider::where('id', $request->id)->update([
                        'descripcion' => $request->nombre,
                        'imagen' => $nombreFoto,
                        'id_producto' => $request->producto,
                        'redireccionamiento' => $request->checkredirec
                    ]);

                    if(Storage::disk('imagenes')->exists($imagenOld)){
                        Storage::disk('imagenes')->delete($imagenOld);
                    }

                    return ['success' => 1];

                }else{
                    return ['success' => 2];
                }
            }else {

                BloqueSlider::where('id', $request->id)->update([
                    'descripcion' => $request->nombre,
                    'id_producto' => $request->producto,
                    'redireccionamiento' => $request->checkredirec
                ]);
            }

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }
}
