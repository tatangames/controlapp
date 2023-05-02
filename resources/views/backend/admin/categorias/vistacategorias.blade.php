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
            <h1>Categorías</h1>

            <button type="button" style="margin-left: 30px" onclick="modalNuevo()" class="btn btn-info btn-sm">
                <i class="fas fa-pencil-alt"></i>
                Nueva Categoría
            </button>
        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" id="card-header-color">
                <h3 class="card-title" style="color: white">Lista de Categorías</h3>
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
                <h4 class="modal-title">Nuevo Categoría</h4>
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

                                <div class="form-group" style="margin-left:0px">
                                    <label>Utiliza Horario?</label><br>
                                    <label class="switch" style="margin-top:10px">
                                        <input type="checkbox" id="toggle-horario">
                                        <div class="slider round">
                                            <span class="on">Si</span>
                                            <span class="off">No</span>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>Horario Mostrar</label>
                                    <input type="time" class="form-control" value="00:00:00" id="hora1">
                                </div>

                                <div class="form-group">
                                    <label>Horario Ocultar</label>
                                    <input type="time" class="form-control" value="00:00:00" id="hora2">
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
                <h4 class="modal-title">Editar Categoría</h4>
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
                                <input type="text" maxlength="100" class="form-control" id="nombre-editar" placeholder="Nombre">
                            </div>

                            <div class="form-group" style="margin-left:0px">
                                <label>Activo</label><br>
                                <label class="switch" style="margin-top:10px">
                                    <input type="checkbox" id="toggle-activo">
                                    <div class="slider round">
                                        <span class="on">Activo</span>
                                        <span class="off">Inactivo</span>
                                    </div>
                                </label>
                            </div>

                            <div class="form-group" style="margin-left:0px">
                                <label>Visible</label><br>
                                <label class="switch" style="margin-top:10px">
                                    <input type="checkbox" id="toggle-visible">
                                    <div class="slider round">
                                        <span class="on">Activo</span>
                                        <span class="off">Inactivo</span>
                                    </div>
                                </label>
                            </div>

                            <div class="form-group" style="margin-left:0px">
                                <label>Utiliza Horario?</label><br>
                                <label class="switch" style="margin-top:10px">
                                    <input type="checkbox" id="toggle-horario-editar">
                                    <div class="slider round">
                                        <span class="on">Si</span>
                                        <span class="off">No</span>
                                    </div>
                                </label>
                            </div>

                            <div class="form-group">
                                <label>Horario Mostrar</label>
                                <input type="time" class="form-control" value="00:00:00" id="hora1-editar">
                            </div>

                            <div class="form-group">
                                <label>Horario Ocultar</label>
                                <input type="time" class="form-control" value="00:00:00" id="hora2-editar">
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

    <script src="{{ asset('js/jquery-ui-drag.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-drag.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            var id = {{ $id }};
            var ruta = "{{ URL::to('/admin/categorias/tablas') }}/" + id;
            $('#tablaDatatable').load(ruta);
        });
    </script>

    <script>

        function recargar(){
            var id = {{ $id }};
            var ruta = "{{ URL::to('/admin/categorias/tablas') }}/" + id;
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
            var hora1 = document.getElementById('hora1').value;
            var hora2 = document.getElementById('hora2').value;
            var tp = document.getElementById('toggle-horario').checked;

            var toggleHorario = tp ? 1 : 0;

            if(hora1 === ''){
                toastr.error('Horario Mostrar es Requerido');
                return;
            }

            if(hora2 === ''){
                toastr.error('Horario Ocultar es Requerido');
                return;
            }

            if(nombre === '') {
                toastr.error('Nombre es requerido');
                return;
            }

            if(nombre.length > 100){
                toastr.error('Nombre máximo 100 caracteres');
                return;
            }

            openLoading();

            var id = {{ $id }};

            var formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('toggle', toggleHorario);
            formData.append('hora1', hora1);
            formData.append('hora2', hora2);
            formData.append('id', id);

            axios.post('/admin/categorias/nuevo', formData, {
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

            axios.post('/admin/categorias/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#modalEditar').modal('show');
                        $('#id-editar').val(id);
                        $('#nombre-editar').val(response.data.categoria.nombre);

                        if(response.data.categoria.activo === 0){
                            $("#toggle-activo").prop("checked", false);
                        }else{
                            $("#toggle-activo").prop("checked", true);
                        }

                        if(response.data.categoria.visible === 0){
                            $("#toggle-visible").prop("checked", false);
                        }else{
                            $("#toggle-visible").prop("checked", true);
                        }

                        $('#hora1-editar').val(response.data.categoria.hora1);
                        $('#hora2-editar').val(response.data.categoria.hora2);

                        if(response.data.categoria.usahorario === 0){
                            $("#toggle-horario-editar").prop("checked", false);
                        }else{
                            $("#toggle-horario-editar").prop("checked", true);
                        }

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

            var ide = document.getElementById('id-editar').value;
            var nombre = document.getElementById('nombre-editar').value;
            var cbactivo = document.getElementById('toggle-activo').checked;
            var cbvisible = document.getElementById('toggle-visible').checked;
            var tp = document.getElementById('toggle-horario-editar').checked;
            var hora1 = document.getElementById('hora1-editar').value;
            var hora2 = document.getElementById('hora2-editar').value;

            var toggleHorario = tp ? 1 : 0;

            if(hora1 === ''){
                toastr.error('Horario Mostrar es Requerido');
                return;
            }

            if(hora2 === ''){
                toastr.error('Horario Ocultar es Requerido');
                return;
            }

            var check_activo = cbactivo ? 1 : 0;
            var check_visible = cbvisible ? 1 : 0;

            if(nombre === '') {
                toastr.error('Nombre es requerido');
                return;
            }

            if(nombre.length > 100){
                toastr.error('Nombre máximo 100 caracteres');
                return;
            }

            // bloque de servicios
            var id = {{ $id }};

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('ide', ide); // a modificar
            formData.append('nombre', nombre);
            formData.append('cbactivo', check_activo);
            formData.append('cbvisible', check_visible);
            formData.append('toggle', toggleHorario);
            formData.append('hora1', hora1);
            formData.append('hora2', hora2);

            axios.post('/admin/categorias/editar', formData, {
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

        function verProductos(id) {
            window.location.href="{{ url('/admin/productos/') }}/"+id;
        }


    </script>


@endsection
