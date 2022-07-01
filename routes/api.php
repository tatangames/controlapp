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


// ****--------------  AFILIADOS  ---------------- **** //

Route::post('afiliado/login', [ApiAfiliadosController::class, 'loginAfiliado']);
Route::post('afiliado/nueva/ordenes', [ApiAfiliadosController::class, 'nuevasOrdenes']);
Route::post('afiliado/categorias/ver-posiciones', [ApiCategoriaAfiliadoController::class, 'informacionCategoriasPosiciones']);
Route::post('afiliado/posiciones/actualizar-categorias', [ApiCategoriaAfiliadoController::class, 'guardarPosicionCategorias']);
Route::post('afiliado/categorias/actualizar-datos', [ApiCategoriaAfiliadoController::class, 'actualizarDatosCategoria']);
Route::post('afiliado/listado/productos-posicion-lista', [ApiCategoriaAfiliadoController::class, 'listadoProductoPosicion']);
Route::post('afiliado/actualizar/productos-posicion', [ApiCategoriaAfiliadoController::class, 'actualizarProductosPosicion']);
Route::post('afiliado/listado/categorias', [ApiCategoriaAfiliadoController::class, 'listadoCategoriasProducto']);
Route::post('afiliado/listado/categorias/producto', [ApiCategoriaAfiliadoController::class, 'listadoCategoriasProductoListado']);
Route::post('afiliado/producto/info/individual', [ApiCategoriaAfiliadoController::class, 'informacionProductoIndividual']);
Route::post('afiliado/actualizar/producto/informacion', [ApiCategoriaAfiliadoController::class, 'actualizarProducto']);
Route::post('afiliado/informacion/cuenta', [ApiAfiliadosController::class, 'informacionCuenta']);
Route::post('afiliado/informacion/disponibilidad', [ApiAfiliadosController::class, 'informacionDisponibilidad']);
Route::post('afiliado/guardar/disponibilidad', [ApiAfiliadosController::class, 'guardarEstados']);
Route::post('afiliado/actualizar/password', [ApiAfiliadosController::class, 'actualizarPasswordAfiliado']);
Route::post('afiliado/listado/horarios', [ApiAfiliadosController::class, 'listadoHorarios']);
Route::post('afiliado/informacion/cerrado', [ApiAfiliadosController::class, 'informacionCerrado']);
Route::post('afiliado/guardar/cerrado', [ApiAfiliadosController::class, 'guardarEstadosCerrado']);

Route::post('afiliado/informacion/estado/nueva-orden', [ApiCategoriaAfiliadoController::class, 'informacionEstadoNuevaOrden']);
Route::post('afiliado/listado/producto/orden', [ApiCategoriaAfiliadoController::class, 'listadoProductosOrden']);
Route::post('afiliado/listado/orden/producto/individual', [ApiCategoriaAfiliadoController::class, 'listaOrdenProductoIndividual']);

// notificacion: orden cancelada por propietario
Route::post('afiliado/cancelar/orden', [ApiCategoriaAfiliadoController::class, 'cancelarOrden']);
Route::post('afiliado/borrar/orden', [ApiCategoriaAfiliadoController::class, 'borrarOrden']);

// notificacion: orden aceptada
Route::post('afiliado/proceso/orden/estado-2', [ApiCategoriaAfiliadoController::class, 'procesarOrdenEstado2']);

Route::post('afiliado/listado/preparando/ordenes', [ApiCategoriaAfiliadoController::class, 'listadoPreparandoOrdenes']);
Route::post('afiliado/informacion/orden/preparando', [ApiCategoriaAfiliadoController::class, 'informacionOrdenEnPreparacion']);

// notificacion: orden preparada por propietario
// notificacion al cliente si el envio fuera para recoger en local, notificar orden lista para entrega
Route::post('afiliado/finalizar/orden', [ApiCategoriaAfiliadoController::class, 'finalizarOrden']);
Route::get('afiliado/ordenes/completadas/hoy', [ApiCategoriaAfiliadoController::class, 'listadoOrdenesCompletadasHoy']);

Route::post('afiliado/historial/ordenes', [ApiCategoriaAfiliadoController::class, 'historialOrdenesCompletas']);

// actualizar cantidad de producto por propietario
Route::post('afiliado/producto/cambiar-cantidad', [ApiCategoriaAfiliadoController::class, 'actualizarCantidadProducto']);

// informacion horario categoria
Route::post('afiliado/categoria/horario-info', [ApiCategoriaAfiliadoController::class, 'infoCategoriaHorario']);

// guardar horario categoria
Route::post('afiliado/categoria/horario-guardar', [ApiCategoriaAfiliadoController::class, 'guardarCategoriaHorario']);



// ****--------------  MOTORISTAS  ---------------- **** //
Route::post('motorista/login', [ApiMotoristasController::class, 'loginMotorista']);
Route::post('motorista/ver/nueva/ordenes', [ApiMotoristasController::class, 'verNuevasOrdenes']);
Route::post('motorista/ver/orden/id', [ApiMotoristasController::class, 'verOrdenPorID']);
Route::post('motorista/ver/productos',  [ApiMotoristasController::class, 'verProductosOrden']);
Route::post('motorista/obtener/orden', [ApiMotoristasController::class, 'obtenerOrden']);
Route::post('motorista/orden/proceso',  [ApiMotoristasController::class, 'verProcesoOrdenes']);
Route::post('motorista/ver/orden/proceso/id', [ApiMotoristasController::class, 'verOrdenProcesoPorID']);

// notificacion: iniciar entrega de la orden
Route::post('motorista/iniciar/entrega', [ApiMotoristasController::class, 'iniciarEntrega']);
Route::post('motorista/info/disponibilidad', [ApiMotoristasController::class, 'informacionDisponibilidad']);
Route::post('motorista/guadar/configuracion', [ApiMotoristasController::class, 'modificarDisponibilidad']);
Route::post('motorista/info/cuenta', [ApiMotoristasController::class, 'informacionCuenta']);
Route::post('motorista/actualizar/password', [ApiMotoristasController::class, 'actualizarPassword']);
Route::post('motorista/orden/procesoentrega', [ApiMotoristasController::class, 'verProcesoOrdenesEntrega']);

// notificacion: orden entregada
Route::post('motorista/finalizar/entrega', [ApiMotoristasController::class, 'finalizarEntrega']);
Route::post('motorista/ver/historial', [ApiMotoristasController::class, 'verHistorial']);
