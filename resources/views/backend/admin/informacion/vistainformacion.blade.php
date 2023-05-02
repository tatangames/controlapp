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
            <h1>Configuración</h1>
        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" id="card-header-color">
                <h3 class="card-title" style="color: white">Información</h3>
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

<!-- modal editar -->
<div class="modal fade" id="modalEditar">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Opciones</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-editar">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group" style="margin-left:0px">
                                    <label>Cerrado de Aplicación</label><br>
                                    <label class="switch" style="margin-top:10px">
                                        <input type="checkbox" id="toggle-cerrado">
                                        <div class="slider round">
                                            <span class="on">Activar</span>
                                            <span class="off">Desactivar</span>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>Mensaje de Cerrado</label>
                                    <input type="text" maxlength="300" class="form-control" id="mensaje">
                                    <input type="hidden" id="id-editar">
                                </div>


                                <div class="form-group" style="margin-left:0px">
                                    <label>Permitir Domicilio y Local Entrega</label><br>
                                    <label class="switch" style="margin-top:10px">
                                        <input type="checkbox" id="toggle-noti">
                                        <div class="slider round">
                                            <span class="on">Domicilio y Local</span>
                                            <span class="off">Domicilio</span>
                                        </div>
                                    </label>
                                </div>


                                <hr>

                                <div class="form-group">
                                    <label>Esto es el Carrusel de Imagenes que se muestra al Inicio</label>
                                </div>

                                <div class="form-group" style="margin-left:0px">
                                    <label>Habilitar Slider</label><br>
                                    <label class="switch" style="margin-top:10px">
                                        <input type="checkbox" id="toggle-carrusel">
                                        <div class="slider round">
                                            <span class="on">Si</span>
                                            <span class="off">No</span>
                                        </div>
                                    </label>
                                </div>



                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnGuardar" onclick="editar()">Guardar</button>
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
            var ruta = "{{ URL::to('/admin/configuracion/tablas') }}";
            $('#tablaDatatable').load(ruta);
        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ url('/admin/configuracion/tablas') }}";
            $('#tablaDatatable').load(ruta);
        }

        function verInformacion(id){

            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post('/admin/configuracion/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        $('#modalEditar').modal('show');
                        $('#id-editar').val(id);
                        $('#mensaje').val(response.data.info.mensaje_cerrado);

                        if(response.data.info.cerrado === 0){
                            $("#toggle-cerrado").prop("checked", false);
                        }else{
                            $("#toggle-cerrado").prop("checked", true);
                        }

                        if(response.data.info.domicilio === 0){
                            $("#toggle-noti").prop("checked", false);
                        }else{
                            $("#toggle-noti").prop("checked", true);
                        }

                        if(response.data.info.activo_slider === 0){
                            $("#toggle-carrusel").prop("checked", false);
                        }else{
                            $("#toggle-carrusel").prop("checked", true);
                        }

                    }else{
                        toastr.error('Error al Buscar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al Buscar');
                    closeLoading();
                });
        }

        function editar() {
            var id = document.getElementById('id-editar').value;
            var mensaje = document.getElementById('mensaje').value;
            var tp = document.getElementById('toggle-cerrado').checked;
            var tn = document.getElementById('toggle-noti').checked;
            var cbslider = document.getElementById('toggle-carrusel').checked;


            var toggleCerrado = tp ? 1 : 0;
            var toggleNoti = tn ? 1 : 0;
            var toggleCarrusel = cbslider ? 1 : 0;


            if(mensaje === ''){
                toastr.error('Mensaje de Cerrado es Requerido');
                return;
            }

            if(mensaje.length > 300){
                toastr.error('Mensaje de Cerrado máximo 300 caracteres');
                return;
            }

            openLoading();

            let formData = new FormData();
            formData.append('id', id);
            formData.append('mensaje', mensaje);
            formData.append('cbcerrado', toggleCerrado);
            formData.append('cbnoti', toggleNoti);
            formData.append('cbcarrusel', toggleCarrusel);

            axios.post('/admin/configuracion/editar', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {
                        $('#modalEditar').modal('hide');
                        toastr.success('Información Actualizada');
                        recargar();
                    } else {
                        toastr.error('Error al Editar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al Editar');
                    closeLoading();
                });
        }

    </script>


@endsection
