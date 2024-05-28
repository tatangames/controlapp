<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th># Orden</th>
                                <th>Fecha</th>
                                <th>Venta</th>
                                <th>Nota cliente</th>
                                <th>Cliente</th>
                                <th>Dirección</th>
                                <th>Referencia</th>
                                <th>Teléfono</th>
                                <th>Ticket</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($ordenes as $dato)
                                <tr>

                                    <td>{{ $dato->id }}</td>
                                    <td>{{ $dato->fecha_orden }}</td>
                                    <td>{{ $dato->precio_consumido }}</td>
                                    <td>{{ $dato->nota }}</td>
                                    <td>{{ $dato->cliente }}</td>
                                    <td>{{ $dato->direccion }}</td>
                                    <td>{{ $dato->referencia }}</td>
                                    <td>{{ $dato->telefono }}</td>
                                    <td>

                                        <button type="button" class="btn btn-primary btn-xs" onclick="imprimirTicket({{ $dato->id }})">
                                            <i class="fas fa-print" title="Ticket"></i>&nbsp; Ticket
                                        </button>

                                    </td>
                                    <td>

                                        <button type="button" class="btn btn-success btn-xs" onclick="informacionProducto({{ $dato->id }})">
                                            <i class="fas fa-shopping-cart" title="Productos"></i>&nbsp; Productos
                                        </button>

                                        <br> <br>
                                        <button type="button" class="btn btn-danger btn-xs" onclick="informacionCancelar({{ $dato->id }})">
                                            <i class="fas fa-info" title="Cancelar"></i>&nbsp; Cancelar
                                        </button>

                                    </td>
                                </tr>
                            @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(function () {
        $("#tabla").DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "pagingType": "full_numbers",
            "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "Todo"]],
            "language": {

                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }

            },
            "responsive": true, "lengthChange": true, "autoWidth": false,
        });
    });



</script>
