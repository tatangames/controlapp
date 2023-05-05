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
            <h1>Ordenes Pendientes</h1>
        </div>

        <button type="button" onclick="recargar()" class="btn btn-success btn-sm">
            <i class="fas fa-pencil-alt"></i>
            Recargar
        </button>

        <div class="form-group" style="width: 25%">
            <label>Cronometro</label>
            <label id="contador"></label>
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

            var ruta = "{{ URL::to('/admin/ordenes-pendientes/tabla/lista') }}";
            $('#tablaDatatable').load(ruta);

            countdown();
        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ url('/admin/ordenes-pendientes/tabla/lista') }}";
            $('#tablaDatatable').load(ruta);
        }

        function countdown() {
            var seconds = 60;
            function tick() {
                var counter = document.getElementById("contador");
                seconds--;
                counter.innerHTML = "0:" + (seconds < 10 ? "0" : "") + String(seconds);
                if( seconds > 0 ) {
                    setTimeout(tick, 1000);
                } else {
                    recargar();
                    countdown();
                }
            }
            tick();
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


        // iniciar la orden
        function informacionIniciar(id){

            Swal.fire({
                title: 'Iniciar Orden?',
                text: "",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    iniciarOrden(id);
                }
            })
        }

        function iniciarOrden(id){

            openLoading();
            document.getElementById("formulario-cliente").reset();

            axios.post('/admin/ordenes/iniciar',{
                'id': id
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        recargar();

                        // si el cliente cancelo la orden

                        Swal.fire({
                            title: 'ORDEN CANCELADA',
                            text: "El Cliente Cancelo la Orden",
                            icon: 'danger',
                            showCancelButton: false,
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Cancelar',
                            confirmButtonText: 'Recargar'
                        }).then((result) => {
                            if (result.isConfirmed) {

                            }
                        })

                    }
                    else  if(response.data.success === 2){

                        recargar();

                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Orden Iniciada',
                            showConfirmButton: false,
                            timer: 1200
                        })

                    }
                    else{
                        toastr.error('Reintentar de nuevo');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Reintentar de nuevo');
                });
        }

        // id de la orden
        function verMapa(id){
            window.location.href="{{ url('/admin/ordenes/mapa/cliente') }}/"+id;
        }



    </script>


@endsection
