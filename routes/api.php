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


// --- CLIENTES ---
Route::post('cliente/login', [ApiClienteController::class, 'loginCliente']);
Route::post('cliente/registro', [ApiRegistroController::class, 'registroCliente']);

// --- PERFIL ---
Route::post('cliente/actualizar/password', [ApiClienteController::class, 'actualizarPasswordCliente']);
Route::post('cliente/listado/direcciones', [ApiPerfilController::class, 'listadoDeDirecciones']);
Route::post('cliente/seleccionar/direccion', [ApiPerfilController::class, 'seleccionarDireccion']);
Route::post('cliente/eliminar/direccion',  [ApiPerfilController::class, 'eliminarDireccion']);
Route::post('cliente/nueva/direccion', [ApiPerfilController::class, 'nuevaDireccionCliente']);
Route::post('cliente/perfil/cambiar-password', [ApiPerfilController::class, 'cambiarPasswordPerfil']);

// --- BLOQUE DE SERVICIOS ---
Route::post('cliente/lista/servicios-bloque', [ApiZonasServiciosController::class, 'listadoBloque']);
Route::post('cliente/servicios/listado/menu', [ApiServiciosController::class, 'listadoMenuVertical']);
Route::post('cliente/informacion/producto', [ApiProductosController::class, 'infoProductoIndividual']);
Route::post('cliente/carrito/producto/agregar', [ApiProductosController::class, 'agregarProductoCarritoTemporal']);

// --- CARRITO DE COMPRAS ---
Route::post('cliente/carrito/ver/orden', [ApiCarritoController::class, 'verCarritoDeCompras']);
Route::post('cliente/carrito/borrar/orden', [ApiCarritoController::class, 'borrarCarritoDeCompras']);
Route::post('cliente/carrito/ver/producto', [ApiCarritoController::class, 'verProductoCarritoEditar']);
Route::post('cliente/carrito/eliminar/producto', [ApiCarritoController::class, 'borrarProductoDelCarrito']);
Route::post('cliente/carrito/cambiar/cantidad', [ApiCarritoController::class, 'editarCantidadProducto']);
Route::post('cliente/carrito/ver/proceso-orden', [ApiCarritoController::class, 'verOrdenAProcesarCliente']);
Route::post('cliente/proceso/orden/estado-1', [ApiCarritoController::class, 'procesarOrdenEstado1']);

// --- ORDENES ENVIADAS ---
Route::post('cliente/ver/ordenes-activas',  [ApiOrdenesController::class, 'ordenesActivas']);
Route::post('cliente/ver/estado-orden',  [ApiOrdenesController::class, 'estadoOrdenesActivas']);
Route::post('cliente/listado/productos/ordenes',  [ApiOrdenesController::class, 'listadoProductosOrdenes']);
Route::post('cliente/proceso/orden/cancelar',  [ApiOrdenesController::class, 'cancelarOrdenCliente']);
Route::post('cliente/proceso/completar/orden',  [ApiOrdenesController::class, 'completarOrden']);










