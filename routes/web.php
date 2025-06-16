<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\Login\LoginController;
use App\Http\Controllers\Backend\Admin\Control\ControlController;
use App\Http\Controllers\Backend\Admin\Control\RolesController;
use App\Http\Controllers\Backend\Admin\Control\PermisosController;
use App\Http\Controllers\Backend\Admin\Perfil\PerfilController;
use App\Http\Controllers\Backend\Admin\Control\EstadisticasController;
use App\Http\Controllers\Backend\Admin\Mapa\ZonaController;
use App\Http\Controllers\Backend\Admin\Servicios\CategoriasController;
use App\Http\Controllers\Backend\Admin\Clientes\ClientesController;
use App\Http\Controllers\Backend\Admin\Eventos\EventosController;
use App\Http\Controllers\Backend\Admin\Horario\HorarioController;
use App\Http\Controllers\Backend\Admin\Ordenes\OrdenesController;
use App\Http\Controllers\Backend\Admin\Motorista\MotoristaController;



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


// --- ORDENES TODAS---
Route::get('/admin/ordenes/todas', [OrdenesController::class,'index'])->name('index.ordenes.todas');
Route::get('/admin/ordenes/todas/tablas', [OrdenesController::class,'tablaOrdenesTodas']);


// --- ORDENES CANCELADAS---
Route::get('/admin/ordenes-canceladas/todas', [OrdenesController::class,'indexOrdenesCanceladas'])->name('index.ordenes.canceladas');
Route::get('/admin/ordenes-canceladas/todas/tablas', [OrdenesController::class,'tablaOrdenesTodasCanceladas']);



// PRODUCTOS DE LA ORDEN
Route::get('/admin/productos/ordenes/{id}', [OrdenesController::class,'indexProductosOrdenes']);
Route::get('/admin/productos/ordenes/tabla/{id}', [OrdenesController::class,'tablaOrdenesProducto']);



// ORDENES PENDIENTES

Route::get('/admin/ordenes-pendientes/lista', [OrdenesController::class,'indexOrdenesPendientes'])->name('index.ordenes.pendientes');
Route::get('/admin/ordenes-pendientes/tabla/lista', [OrdenesController::class,'tablaOrdenesPendientes']);

Route::get('/admin/ordenes/ticket/{id}', [OrdenesController::class,'imprimirTicketV2']);


// iniciar la orden y se envia notificacion al cliente
Route::post('/admin/ordenes/iniciar', [OrdenesController::class,'iniciarOrden']);
Route::get('/admin/ordenes/mapa/cliente/{id}', [OrdenesController::class,'verMapaCliente']);


// Notificacion al cliente
// cancelar la orden
Route::post('/admin/ordenes/cancelar', [OrdenesController::class,'cancelarOrden']);


// ORDENES INICIADAS HOY
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

// --- PRODUCTOS ---
Route::get('/admin/productos/{id}', [CategoriasController::class,'indexProductos']);
Route::get('/admin/productos/tablas/{id}', [CategoriasController::class,'tablaProductos']);
Route::post('/admin/productos/nuevo', [CategoriasController::class,'nuevoProducto']);
Route::post('/admin/productos/informacion', [CategoriasController::class,'informacionProductos']);
Route::post('/admin/productos/editar', [CategoriasController::class,'editarProductos']);
Route::post('/admin/productos/ordenar', [CategoriasController::class,'ordenarProductos']);



// --- CATEGORIAS ---
Route::get('/admin/categorias/{id}', [CategoriasController::class,'indexCategorias']);
Route::get('/admin/categorias/tablas/{id}', [CategoriasController::class,'tablaCategorias']);
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

// --- TIPO DE SERVICIO ---
Route::get('/admin/tiposervicio/index', [HorarioController::class, 'indexTipoServicio'])->name('index.tiposervicio');
Route::get('/admin/tiposervicio/tabla', [HorarioController::class, 'tablaTipoServicio']);
Route::post('/admin/tiposervicio/nuevo', [HorarioController::class, 'nuevoTipoServicio']);
Route::post('/admin/tiposervicio/informacion', [HorarioController::class,'informacionTipoServicio']);
Route::post('/admin/tiposervicio/editar', [HorarioController::class,'editarTipoServicio']);




//***** MOTORISTAS *******
Route::get('/admin/motorista/index', [MotoristaController::class, 'indexNuevoMotorista'])->name('index.nuevo.motorista');
Route::get('/admin/motorista/tabla', [MotoristaController::class, 'tablaNuevoMotorista']);
Route::post('/admin/motorista/nuevo', [MotoristaController::class, 'nuevoMotorista']);
Route::post('/admin/motorista/informacion', [MotoristaController::class, 'infoMotorista']);
Route::post('/admin/motorista/editar', [MotoristaController::class, 'editarMotorista']);

// MOTORISTA DIRECCIONES

Route::get('/admin/motorista/direccion/index', [MotoristaController::class, 'indexNuevoMotoristaDireccion'])->name('index.nuevo.motorista.direccion');
Route::get('/admin/motorista/direccion/tabla', [MotoristaController::class, 'tablaNuevoMotoristaDireccion']);
Route::post('/admin/motorista/direccion/nuevo', [MotoristaController::class, 'nuevoMotoristaDireccion']);
Route::post('/admin/motorista/direccion/informacion', [MotoristaController::class, 'infoMotoristaDireccion']);
Route::post('/admin/motorista/direccion/editar', [MotoristaController::class, 'editarMotoristaDireccion']);
Route::post('/admin/motorista/direccion/borrar', [MotoristaController::class, 'borrarDireccion']);
Route::get('/admin/motorista/direccion/mapa/{id}', [MotoristaController::class, 'verMapa']);




// EXTRA DIRECCIONES

Route::get('/admin/motorista/direccion-extra/{id}', [MotoristaController::class, 'indexNuevoMotoristaDireccionExtra']);
Route::get('/admin/motorista/direccion-extra/tabla/{id}', [MotoristaController::class, 'tablaNuevoMotoristaDireccionExtra']);
Route::post('/admin/motorista/direccion-extra/borrar', [MotoristaController::class, 'borrarDireccionExtra']);
Route::post('/admin/motorista/direccion-extra/nuevo', [MotoristaController::class, 'nuevoMotoristaDireccionExtra']);
Route::post('/admin/motorista/direccion-extra/informacion', [MotoristaController::class, 'infoMotoristaDireccionExtra']);
Route::post('/admin/motorista/direccion-extra/editar', [MotoristaController::class, 'editarMotoristaDireccionExtra']);
Route::get('/admin/motorista/direccion-extra/mapa/{id}', [MotoristaController::class, 'verMapaDireccionExtra']);


Route::get('/admin/motorista/direccion-todas/index', [MotoristaController::class, 'indexDireccionTodas'])->name('index.nuevo.motorista.direccion.todas');
Route::get('/admin/motorista/direccion-todas/tabla', [MotoristaController::class, 'tablaDireccionTodas']);








