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

            <button type="button" style="margin-left: 30px" onclick="modalNuevo()" class="btn btn-info btn-sm">
                <i class="fas fa-pencil-alt"></i>
                Nueva Dirección
            </button>
        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" id="card-header-color">
                <h3 class="card-title" style="color: white">Lista de Direcciones</h3>
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

<!-- modal agregar -->
<div class="modal fade" id="modalAgregar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nueva Direccion</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-nuevo">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" maxlength="100" class="form-control" autocomplete="off" id="nombre-nuevo" placeholder="Nombre">
                                </div>

                                <div class="form-group">
                                    <label>Dirección</label>
                                    <input type="text" maxlength="500" class="form-control" autocomplete="off" id="direccion-nuevo" placeholder="Dirección">
                                </div>

                                <div class="form-group">
                                    <label>Referencia</label>
                                    <input type="text" maxlength="500" class="form-control" autocomplete="off" id="referencia-nuevo" placeholder="Referencia">
                                </div>

                                <div class="form-group">
                                    <label>Teléfono</label>
                                    <input type="text" maxlength="20" class="form-control" autocomplete="off" id="telefono-nuevo" placeholder="Teléfono">
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label>Latitud</label>
                                    <input type="text" maxlength="100" class="form-control" autocomplete="off" id="latitud-nuevo" placeholder="Latitud">
                                </div>

                                <div class="form-group">
                                    <label>Longitud</label>
                                    <input type="text" maxlength="100" class="form-control" autocomplete="off" id="longitud-nuevo" placeholder="Longitud">
                                </div>


                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="nuevo()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- modal editar -->
<div class="modal fade" id="modalEditar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Dirección</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-editar">
                    <div class="card-body">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="hidden" id="id-editar">
                                <input type="text" maxlength="100" class="form-control" autocomplete="off" id="nombre-editar" placeholder="Nombre">
                            </div>

                            <div class="form-group">
                                <label>Dirección</label>
                                <input type="text" maxlength="500" class="form-control" autocomplete="off" id="direccion-editar" placeholder="Dirección">
                            </div>

                            <div class="form-group">
                                <label>Referencia</label>
                                <input type="text" maxlength="500" class="form-control" autocomplete="off" id="referencia-editar" placeholder="Referencia">
                            </div>

                            <div class="form-group">
                                <label>Teléfono</label>
                                <input type="text" maxlength="20" class="form-control" autocomplete="off" id="telefono-editar" placeholder="Teléfono">
                            </div>

                            <hr>

                            <div class="form-group">
                                <label>Latitud</label>
                                <input type="text" maxlength="100" class="form-control" autocomplete="off" id="latitud-editar" placeholder="Latitud">
                            </div>

                            <div class="form-group">
                                <label>Longitud</label>
                                <input type="text" maxlength="100" class="form-control" autocomplete="off" id="longitud-editar" placeholder="Longitud">
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="editar()">Guardar</button>
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

            var ruta = "{{ URL::to('/admin/motorista/direccion/tabla') }}";
            $('#tablaDatatable').load(ruta);
        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ URL::to('/admin/motorista/direccion/tabla') }}";
            $('#tablaDatatable').load(ruta);
        }

        // abrir modal
        function modalNuevo(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        //nuevo servicio
        function nuevo(){
            var nombre = document.getElementById('nombre-nuevo').value;
            var direccion = document.getElementById('direccion-nuevo').value;
            var referencia = document.getElementById('referencia-nuevo').value;
            var telefono = document.getElementById('telefono-nuevo').value;
            var latitud = document.getElementById('latitud-nuevo').value;
            var longitud = document.getElementById('longitud-nuevo').value;

            openLoading();

            var formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('direccion', direccion);
            formData.append('referencia', referencia);
            formData.append('telefono', telefono);
            formData.append('latitud', latitud);
            formData.append('longitud', longitud);

            axios.post('/admin/motorista/direccion/nuevo', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        $('#modalAgregar').modal('hide');
                        toastr.success('Registrado correctamente');
                        recargar();
                    }
                    else {
                        toastr.error('Error al guardar');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Error al guardar');
                });
        }

        function informacion(id){

            document.getElementById("formulario-editar").reset();
            openLoading();

            axios.post('/admin/motorista/direccion/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#modalEditar').modal('show');
                        $('#id-editar').val(id);
                        $('#nombre-editar').val(response.data.info.nombre);
                        $('#direccion-editar').val(response.data.info.direccion);
                        $('#referencia-editar').val(response.data.info.referencia);
                        $('#telefono-editar').val(response.data.info.telefono);
                        $('#latitud-editar').val(response.data.info.latitud);
                        $('#longitud-editar').val(response.data.info.longitud);

                    }else{
                        toastr.error('Error al buscar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al buscar');
                    closeLoading();
                });
        }

        function editar(){

            var id = document.getElementById('id-editar').value;
            var nombre = document.getElementById('nombre-editar').value;
            var direccion = document.getElementById('direccion-editar').value;
            var referencia = document.getElementById('referencia-editar').value;
            var telefono = document.getElementById('telefono-editar').value;
            var latitud = document.getElementById('latitud-editar').value;
            var longitud = document.getElementById('longitud-editar').value;

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('nombre', nombre);
            formData.append('direccion', direccion);
            formData.append('referencia', referencia);
            formData.append('telefono', telefono);
            formData.append('latitud', latitud);
            formData.append('longitud', longitud);

            axios.post('/admin/motorista/direccion/editar', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        $('#modalEditar').modal('hide');
                        toastr.success('Actualizado correctamente');
                        recargar();
                    }
                    else {
                        toastr.error('Error al Editar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al Editar');
                    closeLoading();
                });
        }

        function extraDireccion(id) {
            window.location.href="{{ url('/admin/motorista/direccion-extra') }}/"+id;
        }



        function infoBorrar(id){
            Swal.fire({
                title: 'Borrar Dirección?',
                text: "",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    borrarDireccion(id);
                }
            })
        }


        function borrarDireccion(id){

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            axios.post('/admin/motorista/direccion/borrar', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        toastr.success('Borrado correctamente');
                        recargar();
                    }
                    else {
                        toastr.error('Error al borrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al borrar');
                    closeLoading();
                });
        }


        function verMapa(id){
            window.location.href="{{ url('/admin/motorista/direccion/mapa') }}/"+id;
        }





    </script>


@endsection
