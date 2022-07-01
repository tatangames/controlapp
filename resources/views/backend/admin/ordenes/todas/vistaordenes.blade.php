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
            <h1>Ordenes</h1>

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



<div class="modal fade" id="modalCliente" style="z-index:1000000000">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cliente</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-cliente">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Zona</label>
                                    <input type="text" readonly class="form-control" id="zona">
                                </div>

                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" readonly class="form-control" id="nombre">
                                </div>

                                <div class="form-group">
                                    <label>Dirección</label>
                                    <input type="text" readonly class="form-control" id="direccion">
                                </div>

                                <div class="form-group">
                                    <label>Punto de Referencia</label>
                                    <input type="text" readonly class="form-control" id="puntoref">
                                </div>


                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" readonly class="form-control" id="telefono">
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
            var ruta = "{{ URL::to('/admin/ordenes/tabla/lista') }}";
            $('#tablaDatatable').load(ruta);
        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ url('/admin/ordenes/tabla/lista') }}";
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


    </script>


@endsection
