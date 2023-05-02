<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\Api\Cliente\ApiClienteController;
use App\Http\Controllers\Backend\Api\Cliente\ApiRegistroController;
use App\Http\Controllers\Backend\Api\Servicios\ApiServiciosController;
use App\Http\Controllers\Backend\Api\Servicios\ApiZonasServiciosController;
use App\Http\Controllers\Backend\Api\Perfil\ApiPerfilController;
use App\Http\Controllers\Backend\Api\Productos\ApiProductosController;
use App\Http\Controllers\Backend\Api\Carrito\ApiCarritoController;
use App\Http\Controllers\Backend\Api\Ordenes\ApiOrdenesController;
use App\Http\Controllers\Backend\Api\Motoristas\ApiMotoristasController;
use App\Http\Controllers\Backend\Api\Afiliados\ApiAfiliadosController;
use App\Http\Controllers\Backend\Api\Afiliados\ApiCategoriaAfiliadoController;

// --- CLIENTES ---
Route::post('cliente/registro', [ApiRegistroController::class, 'registroCliente']);
Route::post('cliente/login', [ApiClienteController::class, 'loginCliente']);
Route::post('cliente/enviar/codigo-correo', [ApiClienteController::class, 'enviarCodigoCorreo']);
Route::post('cliente/verificar/codigo-correo-password', [ApiClienteController::class, 'verificarCodigoCorreoPassword']);
Route::post('cliente/actualizar/password', [ApiClienteController::class, 'actualizarPasswordCliente']);

// --- PERFIL ---
Route::post('cliente/informacion', [ApiPerfilController::class, 'informacionPerfil']);
Route::post('cliente/editar-perfil', [ApiPerfilController::class, 'editarPerfil']);
Route::post('cliente/listado/direcciones', [ApiPerfilController::class, 'listadoDeDirecciones']);
Route::post('cliente/seleccionar/direccion', [ApiPerfilController::class, 'seleccionarDireccion']);
Route::post('cliente/eliminar/direccion',  [ApiPerfilController::class, 'eliminarDireccion']);
Route::get('listado/zonas/poligonos', [ApiPerfilController::class, 'puntosZonaPoligonos']);
Route::post('cliente/nueva/direccion', [ApiPerfilController::class, 'nuevaDireccionCliente']);
Route::post('cliente/perfil/cambiar-password', [ApiPerfilController::class, 'cambiarPasswordPerfil']);

// --- BLOQUE DE SERVICIOS ---
Route::post('cliente/lista/servicios-bloque', [ApiZonasServiciosController::class, 'listadoBloque']);

Route::post('cliente/servicios/listado/menu', [ApiServiciosController::class, 'listadoMenuVertical']);
Route::post('cliente/informacion/producto', [ApiProductosController::class, 'infoProductoIndividual']);
Route::post('cliente/carrito/producto/agregar', [ApiProductosController::class, 'agregarProductoCarritoTemporal']);

Route::post('cliente/carrito/ver/producto', [ApiCarritoController::class, 'verProductoCarritoEditar']);
Route::post('cliente/carrito/ver/orden', [ApiCarritoController::class, 'verCarritoDeCompras']);
Route::post('cliente/carrito/borrar/orden', [ApiCarritoController::class, 'borrarCarritoDeCompras']);
Route::post('cliente/carrito/eliminar/producto', [ApiCarritoController::class, 'borrarProductoDelCarrito']);
Route::post('cliente/carrito/cambiar/cantidad', [ApiCarritoController::class, 'editarCantidadProducto']);
Route::post('cliente/carrito/ver/proceso-orden', [ApiCarritoController::class, 'verOrdenAProcesarCliente']);

// notificacion: enviar orden
Route::post('cliente/proceso/orden/estado-1', [ApiCarritoController::class, 'procesarOrdenEstado1']);

Route::post('cliente/ver/ordenes-activas',  [ApiOrdenesController::class, 'ordenesActivas']);
Route::post('cliente/ver/estado-orden',  [ApiOrdenesController::class, 'estadoOrdenesActivas']);

// notificacion: cancelar orden
Route::post('cliente/proceso/orden/cancelar',  [ApiOrdenesController::class, 'cancelarOrdenCliente']);
Route::post('cliente/listado/productos/ordenes',  [ApiOrdenesController::class, 'listadoProductosOrdenes']);
Route::post('cliente/listado/productos/ordenes-individual',  [ApiOrdenesController::class, 'listadoProductosOrdenesIndividual']);
Route::post('cliente/proceso/borrar/orden',  [ApiOrdenesController::class, 'borrarOrdenCliente']);

Route::post('cliente/ver/historial', [ApiOrdenesController::class, 'verHistorial']);
Route::post('cliente/ver/productos/historial',  [ApiOrdenesController::class, 'verProductosOrdenHistorial']);

// --- EVENTOS ----
Route::get('cliente/eventos/listado', [ApiServiciosController::class, 'listadoEventos']);
Route::post('cliente/eventos-imagen/listado', [ApiServiciosController::class, 'listadoEventosImagenes']);

Route::post('cliente/proceso/calificar/entrega',  [ApiOrdenesController::class, 'calificarEntrega']);

// horarios
Route::get('cliente/horarios', [ApiPerfilController::class, 'listaHorarios']);





