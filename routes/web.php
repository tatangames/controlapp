<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Login\LoginController;
use App\Http\Controllers\Backend\Admin\Control\ControlController;
use App\Http\Controllers\Backend\Admin\Control\RolesController;
use App\Http\Controllers\Backend\Admin\Control\PermisosController;
use App\Http\Controllers\Backend\Admin\Perfil\PerfilController;
use App\Http\Controllers\Backend\Admin\Control\EstadisticasController;
use App\Http\Controllers\Backend\Admin\Mapa\ZonaController;
use App\Http\Controllers\Backend\Admin\Afiliado\AfiliadoController;
use App\Http\Controllers\Backend\Admin\Motorista\MotoristaController;
use App\Http\Controllers\Backend\Admin\Servicios\CategoriasController;
use App\Http\Controllers\Backend\Admin\Clientes\ClientesController;
use App\Http\Controllers\Backend\Admin\Eventos\EventosController;
use App\Http\Controllers\Backend\Admin\Horario\HorarioController;
use App\Http\Controllers\Backend\Admin\Ordenes\OrdenesController;

// INICIO
Route::get('/', [LoginController::class,'index'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');


// --- CONTROL WEB ---
Route::get('/panel', [ControlController::class,'indexRedireccionamiento'])->name('admin.panel');

Route::get('/admin/roles/index', [RolesController::class,'index'])->name('admin.roles.index');
Route::get('/admin/roles/tabla', [RolesController::class,'tablaRoles']);
Route::get('/admin/roles/lista/permisos/{id}', [RolesController::class,'vistaPermisos']);
Route::get('/admin/roles/permisos/tabla/{id}', [RolesController::class,'tablaRolesPermisos']);
Route::post('/admin/roles/permiso/borrar', [RolesController::class, 'borrarPermiso']);
Route::post('/admin/roles/permiso/agregar', [RolesController::class, 'agregarPermiso']);
Route::get('/admin/roles/permisos/lista', [RolesController::class,'listaTodosPermisos']);
Route::get('/admin/roles/permisos-todos/tabla', [RolesController::class,'tablaTodosPermisos']);
Route::post('/admin/roles/borrar-global', [RolesController::class, 'borrarRolGlobal']);

// --- PERMISOS ---
Route::get('/admin/permisos/index', [PermisosController::class,'index'])->name('admin.permisos.index');
Route::get('/admin/permisos/tabla', [PermisosController::class,'tablaUsuarios']);
Route::post('/admin/permisos/nuevo-usuario', [PermisosController::class, 'nuevoUsuario']);
Route::post('/admin/permisos/info-usuario', [PermisosController::class, 'infoUsuario']);
Route::post('/admin/permisos/editar-usuario', [PermisosController::class, 'editarUsuario']);
Route::post('/admin/permisos/nuevo-rol', [PermisosController::class, 'nuevoRol']);
Route::post('/admin/permisos/extra-nuevo', [PermisosController::class, 'nuevoPermisoExtra']);
Route::post('/admin/permisos/extra-borrar', [PermisosController::class, 'borrarPermisoGlobal']);

// --- PERFIL ---
Route::get('/admin/editar-perfil/index', [PerfilController::class,'indexEditarPerfil'])->name('admin.perfil');
Route::post('/admin/editar-perfil/actualizar', [PerfilController::class, 'editarUsuario']);

// --- ESTADISTICAS ---
Route::get('/admin/estadisticas/index', [EstadisticasController::class,'index'])->name('index.estadisticas');

// --- ZONAS ---
Route::get('/admin/zona/mapa/zona', [ZonaController::class,'index'])->name('index.zonas');
Route::get('/admin/zona/tablas/zona', [ZonaController::class,'tablaZonas']);
Route::post('/zona/nueva-zona', [ZonaController::class,'nuevaZona']);
Route::post('/zona/informacion-zona', [ZonaController::class,'informacionZona']);
Route::post('/zona/editar-zona', [ZonaController::class,'editarZona']);
Route::get('admin/zona/ver-mapa/{id}', [ZonaController::class,'verMapa']);

// --- POLIGONO ---
Route::get('/admin/zona/poligono/{id}', [ZonaController::class,'indexPoligono']);
Route::post('/zona/poligono/listado-nuevo', [ZonaController::class,'nuevoPoligono']);
Route::post('/zona/poligono/borrar', [ZonaController::class,'borrarPoligonos']);

// --- AFILIADOS ---
Route::get('/admin/afiliados/lista', [AfiliadoController::class,'index'])->name('index.afiliados');
Route::get('/admin/afiliados/tabla/lista', [AfiliadoController::class,'tablaAfiliados']);
Route::post('/admin/afiliados/nuevo', [AfiliadoController::class,'nuevo']);
Route::post('/admin/afiliados/informacion', [AfiliadoController::class,'informacion']);
Route::post('/admin/afiliados/editar', [AfiliadoController::class,'editar']);

// --- MOTORISTAS ---
Route::get('/admin/motoristas/lista', [MotoristaController::class,'index'])->name('index.motoristas');
Route::get('/admin/motoristas/tabla/lista', [MotoristaController::class,'tablaMotoristas']);
Route::post('/admin/motoristas/nuevo', [MotoristaController::class,'nuevo']);
Route::post('/admin/motoristas/informacion', [MotoristaController::class,'informacion']);
Route::post('/admin/motoristas/editar', [MotoristaController::class,'editar']);

// --- MOTORISTAS ORDENES ---
Route::get('/admin/motoristas-ordenes/lista', [MotoristaController::class,'indexMotoristaOrdenes'])->name('index.motoristas.ordenes');
Route::get('/admin/motoristas-ordenes/tabla/lista', [MotoristaController::class,'tablaMotoristasOrdenes']);
Route::post('/admin/motoristas-ordenes/informacion', [MotoristaController::class,'informacionMotoristaOrden']);
Route::post('/admin/motoristas-ordenes/editar', [MotoristaController::class,'editarMotoristaOrden']);

// --- ORDENES ---
Route::get('/admin/ordenes/lista', [OrdenesController::class,'index'])->name('index.ordenes');
Route::get('/admin/ordenes/tabla/lista', [OrdenesController::class,'tablaOrdenes']);
Route::post('/admin/ordenes/informacion', [OrdenesController::class,'informacionOrden']);

Route::get('/admin/productos/ordenes/{id}', [OrdenesController::class,'indexProductosOrdenes']);
Route::get('/admin/productos/ordenes/tabla/{id}', [OrdenesController::class,'tablaOrdenesProducto']);

Route::get('/admin/ordenes-hoy/lista', [OrdenesController::class,'indexOrdenHoy'])->name('index.ordenes.hoy');
Route::get('/admin/ordenes-hoy/tabla/lista', [OrdenesController::class,'tablaOrdenesHoy']);

// --- BLOQUES ---
Route::get('/admin/bloques', [CategoriasController::class,'indexBloque'])->name('index.bloques');
Route::get('/admin/bloques/tablas/', [CategoriasController::class,'tablaBloque']);
Route::post('/admin/bloques/nuevo', [CategoriasController::class,'nuevoBloque']);
Route::post('/admin/bloques/informacion', [CategoriasController::class,'informacionBloque']);
Route::post('/admin/bloques/editar', [CategoriasController::class,'editarBloque']);
Route::post('/admin/bloques/ordenar', [CategoriasController::class,'ordenarBloque']);

// --- SLIDERS ---
Route::get('/admin/sliders', [CategoriasController::class,'indexSliders'])->name('index.sliders');
Route::get('/admin/sliders/tablas', [CategoriasController::class,'tablaSliders']);
Route::post('/admin/sliders/nuevo', [CategoriasController::class,'nuevoSliders']);
Route::post('/admin/sliders/ordenar', [CategoriasController::class,'ordenarSliders']);
Route::post('/admin/sliders/borrar', [CategoriasController::class,'borrarSliders']);
Route::post('/admin/sliders/informacion', [CategoriasController::class,'informacionSlider']);
Route::post('/admin/sliders/editar', [CategoriasController::class,'editarSlider']);

// --- CATEGORIAS ---
Route::get('/admin/categorias', [CategoriasController::class,'indexCategorias']);
Route::get('/admin/categorias/tablas', [CategoriasController::class,'tablaCategorias']);
Route::post('/admin/categorias/nuevo', [CategoriasController::class,'nuevaCategorias']);
Route::post('/admin/categorias/informacion', [CategoriasController::class,'informacionCategorias']);
Route::post('/admin/categorias/editar', [CategoriasController::class,'editarCategorias']);
Route::post('/admin/categorias/ordenar', [CategoriasController::class,'ordenarCategorias']);

// --- EVENTOS ---
Route::get('/admin/eventos', [EventosController::class,'indexEventos']);
Route::get('/admin/eventos/tablas', [EventosController::class,'tablaEventos']);
Route::post('/admin/eventos/nuevo', [EventosController::class,'nuevoEvento']);
Route::post('/admin/eventos/informacion', [EventosController::class,'informacionEvento']);
Route::post('/admin/eventos/editar', [EventosController::class,'editarEvento']);
Route::post('/admin/eventos/ordenar', [EventosController::class,'ordenarEvento']);
Route::post('/admin/eventos/borrar', [EventosController::class,'borrarEvento']);

// --- EVENTO IMAGENES ---
Route::get('/admin/eventos-imagen/{id}', [EventosController::class,'indexImagenes']);
Route::get('/admin/eventos-imagen/tablas/{id}', [EventosController::class,'tablaImagenes']);
Route::post('/admin/eventos-imagen/nuevo', [EventosController::class,'nuevoEventoImagen']);
Route::post('/admin/eventos-imagen/borrar', [EventosController::class,'borrarEventoImagen']);
Route::post('/admin/eventos-imagen/ordenar', [EventosController::class,'ordenarEventoImagen']);

// --- HORARIO ---
Route::get('/admin/horario', [HorarioController::class,'indexHorario'])->name('index.horario');
Route::get('/admin/horario/tablas', [HorarioController::class,'tablaHorario']);
Route::post('/admin/horario/informacion', [HorarioController::class,'informacionHorario']);
Route::post('/admin/horario/editar', [HorarioController::class,'editarHorario']);

// --- PRODUCTOS ---
Route::get('/admin/productos/{id}', [CategoriasController::class,'indexProductos']);
Route::get('/admin/productos/tablas/{id}', [CategoriasController::class,'tablaProductos']);
Route::post('/admin/productos/nuevo', [CategoriasController::class,'nuevoProducto']);
Route::post('/admin/productos/informacion', [CategoriasController::class,'informacionProductos']);
Route::post('/admin/productos/editar', [CategoriasController::class,'editarProductos']);
Route::post('/admin/productos/ordenar', [CategoriasController::class,'ordenarProductos']);

// --- CLIENTES ---
Route::get('/admin/cliente/lista-clientes-hoy', [ClientesController::class, 'indexRegistradosHoy'])->name('index.clientes.registrados.hoy');
Route::get('/admin/cliente/tablas/cliente-hoy', [ClientesController::class, 'tablaRegistradosHoy']);

Route::get('/admin/cliente/listado', [ClientesController::class, 'indexListaClientes'])->name('index.clientes.listado');
Route::get('/admin/cliente/tabla/listado', [ClientesController::class, 'tablaindexListaClientes']);
Route::post('/admin/cliente/informacion', [ClientesController::class, 'informacionCliente']);
Route::post('/admin/cliente/actualizar/informacion', [ClientesController::class, 'actualizarCliente']);

Route::get('/admin/cliente/lista/direcciones/{id}', [ClientesController::class, 'indexListaDirecciones']);
Route::get('/admin/cliente/lista/tabla-direcciones/{id}', [ClientesController::class, 'tablaIndexListaDirecciones']);

// --- CONFIGURACION ---
Route::get('/admin/configuracion/index', [HorarioController::class, 'indexConfiguracion'])->name('index.configuracion');
Route::get('/admin/configuracion/tablas', [HorarioController::class, 'tablaConfiguracion']);
Route::post('/admin/configuracion/informacion', [HorarioController::class,'informacionConfiguracion']);
Route::post('/admin/configuracion/editar', [HorarioController::class,'editarConfiguracion']);

// --- INTENTOS DE RECUPERACION DE CONTRASEÑA ---
Route::get('/admin/intentos-correo/lista', [HorarioController::class, 'indexIntentosCorreo'])->name('index.intentos.correo');
Route::get('/admin/intentos-correo/tabla/lista', [HorarioController::class, 'tablaIntentosCorreo']);



