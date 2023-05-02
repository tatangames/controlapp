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
            <h1>Zonas de Entrega</h1>

            <button type="button" onclick="abrirModalAgregar()" style="margin-left: 50px" class="btn btn-info btn-sm">
                <i class="fas fa-pencil-alt"></i>
                Nueva Zona
            </button>
        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" id="card-header-color">
                <h3 class="card-title" style="color: white">Lista de Zonas</h3>
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

<!-- modal nuevo -->
<div class="modal fade" id="modalAgregar">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nueva Zona</h4>
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
                                    <input type="hidden" id="id-actualizar">
                                    <input type="text" maxlength="100" class="form-control" id="nombre-nuevo" placeholder="Nombre zona">
                                </div>

                                <div class="form-group">
                                    <label>Latitud</label>
                                    <input type="text" maxlength="50" class="form-control" id="latitud-nuevo" placeholder="Latitud">
                                </div>

                                <div class="form-group">
                                    <label>Longitud</label>
                                    <input type="text" maxlength="50" class="form-control" id="longitud-nuevo" placeholder="Longitud">
                                </div>

                                <div class="form-group">
                                    <label>Precio Mínimo de Consumo</label>
                                    <input type="number" class="form-control" id="precio-nuevo" value="0.00">
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="verificarNuevo()">Guardar</button>
            </div>
        </div>
    </div>
</div>

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
                                    <label>Nombre</label>
                                    <input type="hidden" id="id-editar">
                                    <input type="text" maxlength="100" class="form-control" id="nombre-editar" placeholder="Nombre zona">
                                </div>

                                <div class="form-group">
                                    <label>Latitud</label>
                                    <input type="text" maxlength="50" class="form-control" id="latitud-editar" placeholder="Latitud" required>
                                </div>

                                <div class="form-group">
                                    <label>Longitud</label>
                                    <input type="text" maxlength="50" class="form-control" id="longitud-editar" placeholder="Longitud" required>
                                </div>

                                <div class="form-group">
                                    <label>Precio Mínimo de Consumo</label>
                                    <input type="number" class="form-control" id="precio-editar">
                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="form-group" style="margin-left:0px">
                                    <label>Problema de zona</label><br>
                                    <label class="switch" style="margin-top:10px">
                                        <input type="checkbox" id="toggle-problema">
                                        <div class="slider round">
                                            <span class="on">Activar</span>
                                            <span class="off">Desactivar</span>
                                        </div>
                                    </label>
                                </div>

                                <br>

                                <div class="form-group">
                                    <label>Mensaje Bloqueo (cuando no se puede dar servicio a toda una zona)</label>
                                    <input type="text" maxlength="200" class="form-control" id="mensaje-editar" placeholder="Mensaje Bloqueo">
                                </div>

                                <br>

                                <div class="form-group" style="margin-left:0px">
                                    <label>Disponibilidad Zona (Mostrar/Ocultar en App)</label><br>
                                    <label class="switch" style="margin-top:10px">
                                        <input type="checkbox" id="toggle-activo">
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
                <button type="button" class="btn btn-primary" id="btnGuardar" onclick="verificarEditar()">Guardar</button>
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
            var ruta = "{{ URL::to('/admin/zona/tablas/zona') }}";
            $('#tablaDatatable').load(ruta);
        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ url('/admin/zona/tablas/zona') }}";
            $('#tablaDatatable').load(ruta);
        }

        function abrirModalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function verificarNuevo(){

            Swal.fire({
                title: 'Guardar Nueva zona?',
                text: "",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    nuevo();
                }
            })
        }

        function nuevo() {
            var nombre = document.getElementById('nombre-nuevo').value;

            var latitud = document.getElementById("latitud-nuevo").value;
            var longitud = document.getElementById("longitud-nuevo").value;
            var precio = document.getElementById("precio-nuevo").value;

            if(nombre === ''){
                toastr.error('Nombre de zona es Requerido');
                return;
            }

            if(nombre > 100){
                toastr.error('Nombre máximo 100 caracteres');
                return;
            }


            if(latitud === '') {
                toastr.error('Latitud es Requerido');
                return;
            }

            if(latitud.length > 50){
                toastr.error('Latitud máximo 50 caracteres');
                return;
            }


            if(longitud === '') {
                toastr.error('Longitud es Requerido');
                return;
            }

            if(longitud.length > 50){
                toastr.error('Longitud máximo 50 caracteres');
                return;
            }

            var reglaNumeroDecimal = /^[0-9]\d*(\.\d+)?$/;

            if(!precio.match(reglaNumeroDecimal)) {
                toastr.error('Precio debe ser número decimal');
                return;
            }

            if(precio < 0) {
                toastr.error('Precio debe ser mayor a 0 o igual');
                return;
            }

            if(precio.length > 6) {
                toastr.error('Máximo 6 caracteres');
                return;
            }


            let formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('latitud', latitud);
            formData.append('longitud', longitud);
            formData.append('precio', precio);

            openLoading();

            axios.post('/zona/nueva-zona', formData, {
            })
                .then((response) => {
                    closeLoading()

                    if (response.data.success === 1) {
                        $('#modalAgregar').modal('hide');
                        toastr.success('Registro Agregado');
                        recargar();
                    } else if (response.data.success === 2) {
                        toastr.error('Error al Registrar');
                    }
                    else {
                        toastr.error('Error al Registrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al Registrar');
                    closeLoading()
                });
        }

        function modalOpcion(){
            document.getElementById("formulario-opcion").reset();
            $('#modalOpcion').modal('show');
        }

        function verInformacion(id){

            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post('/zona/informacion-zona',{
                'id': id
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){

                        $('#modalEditar').modal('show');
                        $('#id-editar').val(response.data.zona.id);
                        $('#nombre-editar').val(response.data.zona.nombre);
                        $('#mensaje-editar').val(response.data.zona.mensaje_bloqueo)

                        $('#latitud-editar').val(response.data.zona.latitud);
                        $('#longitud-editar').val(response.data.zona.longitud);
                        $('#precio-editar').val(response.data.zona.minimo_consumo);

                        if(response.data.zona.saturacion === 0){
                            $("#toggle-problema").prop("checked", false);
                        }else{
                            $("#toggle-problema").prop("checked", true);
                        }

                        if(response.data.zona.activo === 0){
                            $("#toggle-activo").prop("checked", false);
                        }else{
                            $("#toggle-activo").prop("checked", true);
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

        function verificarEditar(){

            Swal.fire({
                title: 'Editar Registro?',
                text: "",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    editar();
                }
            })
        }

        function editar() {
            var id = document.getElementById('id-editar').value;
            var nombre = document.getElementById('nombre-editar').value;

            // toggle problema
            var tp = document.getElementById('toggle-problema').checked;
            // toggle activo
            var ta = document.getElementById('toggle-activo').checked;

            var latitud = document.getElementById("latitud-editar").value;
            var longitud = document.getElementById("longitud-editar").value;
            var mensaje = document.getElementById("mensaje-editar").value;

            var precio = document.getElementById("precio-editar").value;

            var toggleProblema = tp ? 1 : 0;
            var toggleActivo = ta ? 1 : 0;

            if(nombre === ''){
                toastr.error('Nombre de zona es Requerido');
                return;
            }

            if(nombre > 100){
                toastr.error('Nombre máximo 100 caracteres');
                return;
            }

            if(latitud === '') {
                toastr.error('Latitud es Requerido');
                return;
            }

            if(latitud.length > 50){
                toastr.error('Latitud máximo 50 caracteres');
                return;
            }


            if(longitud === '') {
                toastr.error('Longitud es Requerido');
                return;
            }

            if(longitud.length > 50){
                toastr.error('Longitud máximo 50 caracteres');
                return;
            }

            if(precio === ''){
                toastr.error('Mínimo consumo es requerido');
                return;
            }

            var reglaNumeroDecimal = /^[0-9]\d*(\.\d+)?$/;

            if(!precio.match(reglaNumeroDecimal)) {
                toastr.error('Mínimo consumo debe ser número decimal');
                return;
            }

            if(precio < 0) {
                toastr.error('Mínimo consumo debe ser mayor a 0 o igual');
                return;
            }

            if(precio.length > 6) {
                toastr.error('Mínimo consumo Máximo 6 caracteres');
                return;
            }

            if(toggleProblema === 1){
                if(mensaje === ''){
                    toastr.error('Mensaje Bloqueo de Zona es requerido');
                    return;
                }

                if(mensaje.length > 200){
                    toastr.error('Mensaje Bloqueo máximo 200 caracteres');
                    return;
                }
            }

            openLoading();

            let formData = new FormData();
            formData.append('id', id);
            formData.append('nombre', nombre);
            formData.append('togglep', toggleProblema);
            formData.append('togglea', toggleActivo);
            formData.append('latitud', latitud);
            formData.append('longitud', longitud);
            formData.append('mensaje', mensaje);
            formData.append('precio', precio);

            axios.post('/zona/editar-zona', formData, {
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

        function vistaPoligonos(id){
            window.location.href="{{ url('/admin/zona/poligono') }}/"+id;
        }

        function verMapa(id){
            window.location.href="{{ url('/admin/zona/ver-mapa/') }}/"+id;
        }


    </script>


@endsection
