@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />

@stop

<section class="content-header">
    <div class="container-fluid">
        <div class="col-sm-12">
            <h1>Ubicación de entrega</h1>
        </div>
    </div>
</section>

<!-- seccion mapa -->
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">

            <div class="card-header">
                <h3 class="card-title">Mapa</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="container">

                        <div id="map" style="width: 100%; height: 700px;"></div>


                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleapi }}&callback=initMap" async defer></script>


    <script>
        function initMap() {
            const ubicacion = { lat: parseFloat("{{ $latitud }}"), lng: parseFloat("{{ $longitud }}") };

            const map = new google.maps.Map(document.getElementById("map"), {
                center: ubicacion,
                zoom: 17
            });

            const marker = new google.maps.Marker({
                position: ubicacion,
                map: map,
                icon: {
                    url: "https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi2.png",
                    labelOrigin: new google.maps.Point(16, -8) // más arriba del marcador
                },
                label: {
                    text: "CLIENTE",
                    color: "black",
                    fontWeight: "bold",
                    fontSize: "14px"
                }
            });

        }
    </script>

@stop



