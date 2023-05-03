@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />

@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }

    #card-header-color {
        background-color: #673AB7 !important;
    }
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <h1>Ordenes HOY: {{ $fecha }}</h1>

        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" id="card-header-color">
                <h3 class="card-title" style="color: white">Listado de Ordenes</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="tablaDatatable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<div class="modal fade" id="modalCancelar" style="z-index:1000000000">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cancelar Orden</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-cancelar">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Explicación al Cliente</label>
                                    <input type="hidden" id="id-cancelar">
                                    <input type="text" maxlength="600" class="form-control" id="nombre-cancelar">
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger" onclick="cancelarOrden()">Cancelar</button>
            </div>
        </div>
    </div>
</div>



@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            var ruta = "{{ URL::to('/admin/ordenes-hoy/tabla/lista') }}";
            $('#tablaDatatable').load(ruta);
        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ url('/admin/ordenes-hoy/tabla/lista') }}";
            $('#tablaDatatable').load(ruta);
        }

        function informacion(id){
            openLoading();
            document.getElementById("formulario-cliente").reset();

            axios.post('/admin/ordenes/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        $('#zona').val(response.data.zona);
                        $.each(response.data.cliente, function( key, val ){
                            $('#nombre').val(val.nombre);
                            $('#direccion').val(val.direccion);
                            $('#telefono').val(val.telefono)
                            $('#puntoref').val(val.punto_referencia)
                        });

                        $('#modalCliente').modal('show');
                    }else{
                        toastr.error('Información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }

        function informacionProducto(id){
            window.location.href="{{ url('/admin/productos/ordenes') }}/"+id;
        }


        function informacionCancelar(id){

            document.getElementById("formulario-cancelar").reset();
            $('#id-cancelar').val(id);
            $('#modalCancelar').modal('show');
        }


        function cancelarOrden(){

            var nombre = document.getElementById('nombre-cancelar').value;
            var id = document.getElementById('id-cancelar').value;

            if(nombre === '') {
                toastr.error('Nota es requerido');
                return;
            }

            if(nombre.length > 600){
                toastr.error('Nota máximo 600 caracteres');
                return;
            }

            openLoading();

            var formData = new FormData();
            formData.append('id', id);
            formData.append('nombre', nombre);

            axios.post('/admin/ordenes/cancelar', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        $('#modalCancelar').modal('hide');
                        toastr.success('Cancelada correctamente');
                        recargar();
                    }
                    else {
                        toastr.error('Error al cancelar');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Error al cancelar');
                });
        }


        // id de la orden
        function verMapa(id){
            window.location.href="{{ url('/admin/ordenes/mapa/cliente') }}/"+id;
        }



    </script>


@endsection
