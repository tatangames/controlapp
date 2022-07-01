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
            <h1>Horarios</h1>
        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" id="card-header-color">
                <h3 class="card-title" style="color: white">Lista de Horarios</h3>
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
                <h4 class="modal-title">Editar Zona</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-editar">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>Día</label>
                                    <input type="text" readonly class="form-control" id="nombre">
                                    <input type="hidden" id="id-editar">
                                </div>

                                <div class="form-group">
                                    <label>Horario cierra Lunes</label>
                                    <input type="time" class="form-control" id="hora1">
                                </div>

                                <div class="form-group">
                                    <label>Horario cierra Lunes</label>
                                    <input type="time" class="form-control" id="hora2">
                                </div>

                                <div class="form-group" style="margin-left:0px">
                                    <label>Cerrado Este Día</label><br>
                                    <label class="switch" style="margin-top:10px">
                                        <input type="checkbox" id="toggle-cerrado">
                                        <div class="slider round">
                                            <span class="on">Activar</span>
                                            <span class="off">Desactivar</span>
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
            var ruta = "{{ URL::to('/admin/horario/tablas') }}";
            $('#tablaDatatable').load(ruta);
        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ url('/admin/horario/tablas') }}";
            $('#tablaDatatable').load(ruta);
        }

        function verInformacion(id){

            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post('/admin/horario/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        $('#modalEditar').modal('show');
                        $('#id-editar').val(id);
                        $('#nombre').val(response.data.horario.nombre);
                        $('#hora1').val(response.data.horario.hora1);
                        $('#hora2').val(response.data.horario.hora2);

                        if(response.data.horario.cerrado === 0){
                            $("#toggle-cerrado").prop("checked", false);
                        }else{
                            $("#toggle-cerrado").prop("checked", true);
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
            var hora1 = document.getElementById('hora1').value;
            var hora2 = document.getElementById('hora2').value;
            var tp = document.getElementById('toggle-cerrado').checked;

            var toggleCerrado = tp ? 1 : 0;

            if(hora1 === ''){
                toastr.error('Horario Abre es Requerido');
                return;
            }

            if(hora2 === ''){
                toastr.error('Horario Cierre es Requerido');
                return;
            }

            openLoading();

            let formData = new FormData();
            formData.append('id', id);
            formData.append('hora1', hora1);
            formData.append('hora2', hora2);
            formData.append('cbcerrado', toggleCerrado);

            axios.post('/admin/horario/editar', formData, {
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
