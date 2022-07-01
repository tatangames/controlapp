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
            <h1>Motoristas</h1>

            <button style="margin-left: 30px" type="button" onclick="modalAgregar()" class="btn btn-info btn-sm">
                <i class="fas fa-pencil-alt"></i>
                Nuevo Motorista
            </button>
        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" id="card-header-color">
                <h3 class="card-title" style="color: white">Listado de Motoristas</h3>
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

<!-- modal nuevo-->
<div class="modal fade" id="modalAgregar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Motorista</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-agregar">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" maxlength="50" class="form-control" autocomplete="off" id="nombre-nuevo" placeholder="Nombre">
                                </div>

                                <div class="form-group">
                                    <label>Usuario</label>
                                    <input type="text" maxlength="25" autocomplete="off" class="form-control" id="usuario-nuevo" placeholder="Usuario">
                                </div>

                                <div class="form-group">
                                    <label>Contraseña</label>
                                    <input type="text" maxlength="16" class="form-control" autocomplete="off" id="pass-nuevo" placeholder="12345678">
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

<!-- modal editar-->
<div class="modal fade" id="modalEditar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Motorista</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-editar">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="hidden" id="id-editar">
                                    <input type="text" maxlength="50" class="form-control" id="nombre-editar" placeholder="Nombre">
                                </div>

                                <div class="form-group">
                                    <label>Usuario</label>
                                    <input type="text" maxlength="25" class="form-control" id="usuario-editar" placeholder="Usuario">
                                </div>

                                <div class="form-group" style="margin-left:20px">
                                    <label>Activo</label><br>
                                    <label class="switch" style="margin-top:10px">
                                        <input type="checkbox" id="activo-editar">
                                        <div class="slider round">
                                            <span class="on">Si</span>
                                            <span class="off">No</span>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-group" style="margin-left:20px">
                                    <label>Si se activa, se resetea la contraseña a '12345678'</label><br>
                                    <label class="switch" style="margin-top:10px">
                                        <input type="checkbox" id="password-editar">
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
            var ruta = "{{ URL::to('/admin/motoristas/tabla/lista') }}";
            $('#tablaDatatable').load(ruta);
        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ url('/admin/motoristas/tabla/lista') }}";
            $('#tablaDatatable').load(ruta);
        }

        function modalAgregar(){
            document.getElementById("formulario-agregar").reset();
            $('#modalAgregar').modal('show');
        }

        function nuevo(){
            var nombre = document.getElementById('nombre-nuevo').value;
            var usuario = document.getElementById('usuario-nuevo').value;
            var password = document.getElementById('pass-nuevo').value;

            if(nombre === ''){
                toastr.error('Nombre es requerido');
                return;
            }

            if(nombre.length > 50){
                toastr.error('Nombre máximo 50 caracteres');
                return;
            }

            if(usuario === ''){
                toastr.error('Usuario es requerido');
                return;
            }

            if(usuario.length > 25){
                toastr.error('Usuario máximo 25 caracteres');
                return;
            }

            if(password === ''){
                toastr.error('Contraseña es requerido');
                return;
            }

            if(password.length < 4) {
                toastr.error('Mínimo 4 caracteres para Contraseña');
                return;
            }

            if(password.length > 16) {
                toastr.error('Máximo 16 caracteres para Contraseña');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('usuario', usuario);
            formData.append('password', password);

            axios.post('/admin/motoristas/nuevo', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        toastr.error('El Usuario ya se encuentra Registrado');
                    }
                    else if (response.data.success === 2) {
                        toastr.success('Actualizado correctamente');
                        $('#modalAgregar').modal('hide');
                        recargar();
                    }
                    else {
                        toastr.error('Error al Registrar');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('');
                    toastr.error('Error al Registrar');
                });
        }

        function informacion(id){
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post('/admin/motoristas/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        $('#id-editar').val(response.data.afiliado.id);
                        $('#nombre-editar').val(response.data.afiliado.nombre);
                        $('#usuario-editar').val(response.data.afiliado.usuario);

                        if(response.data.afiliado.activo === 0){
                            $("#activo-editar").prop("checked", false);
                        }else{
                            $("#activo-editar").prop("checked", true);
                        }

                        $('#modalEditar').modal('show');
                    }else{
                        toastr.error('Información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }

        function editar(){

            var id = document.getElementById('id-editar').value;
            var nombre = document.getElementById('nombre-editar').value;
            var usuario = document.getElementById('usuario-editar').value;
            var ta = document.getElementById('activo-editar').checked;
            var tp = document.getElementById('password-editar').checked;

            var toggleActivo = ta ? 1 : 0;
            var togglePassword = tp ? 1 : 0;

            if(nombre === ''){
                toastr.error('Nombre es requerido');
                return;
            }

            if(nombre.length > 50){
                toastr.error('Nombre máximo 50 caracteres');
                return;
            }

            if(usuario === ''){
                toastr.error('Usuario es requerido');
                return;
            }

            if(usuario.length > 25){
                toastr.error('Usuario máximo 25 caracteres');
                return;
            }

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('nombre', nombre);
            formData.append('usuario', usuario);
            formData.append('activo', toggleActivo);
            formData.append('passcheck', togglePassword);

            axios.post('/admin/motoristas/editar', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        toastr.error('El Usuario ya esta registrado');
                    }
                    else if(response.data.success === 2){
                        toastr.success('Actualizado correctamente');
                        $('#modalEditar').modal('hide');
                        recargar();
                    }
                    else {
                        toastr.error('Error al Actualizar');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Error al Actualizar');
                });
        }


    </script>


@endsection
