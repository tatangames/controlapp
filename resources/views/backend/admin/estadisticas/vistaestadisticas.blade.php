@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
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
        <div class="col-sm-12">
            <h1>Estadísticas de Aplicación</h1>
        </div>
        <br>

    </div>
</section>

<section class="content">

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header" id="card-header-color">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-shopping-cart" style="color: white"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Ordenes: </span>
                                    <span class="info-box-number">{{ $tordenes }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box bg-gray-dark">
                                <span class="info-box-icon"><i class="fas fa-shopping-cart" style="color: white"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Venta Total:</span>
                                    <span class="info-box-number">${{ $vtotal }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="far fa-user" style="color: white"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cliente Registrados Hoy: </span>
                                    <span class="info-box-number">{{ $clientehoy }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box bg-cyan">
                                <span class="info-box-icon"><i class="far fa-user" style="color: white"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Clientes Registrados: </span>
                                    <span class="info-box-number">{{ $clientetotal }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>


</section>

@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <!-- incluir tabla -->
    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>

    <script>



    </script>



@stop
