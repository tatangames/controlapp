<style>

    * {
        font-family: 'Times New Roman';


        margin-left: 5px !important;
        margin-top: 3px !important;
        margin-right: 0px !important;
    }

    td,
    th,
    tr,
    table {
        border-top: 1px solid black;
        border-collapse: collapse;
    }

    td.description,
    th.description {
        width: 100px;
        max-width: 100px;
    }

    td.quantity,
    th.quantity {
        width: 35px;
        max-width: 35px;
        word-break: break-all;
    }

    td.price,
    th.price {
        width: 40px;
        max-width: 40px;
        word-break: break-all;
        margin-left: 5px !important;
    }

    .centered {
        text-align: center;
        align-content: center;
    }

    .izquierdazo {
        text-align: left;

    }

    .ticket {
        width: 250px;
        max-width: 250px;
    }

    img {
        width: 120px;
        height: 90px;
        align-content: center;
        margin-right: 15px !important;
    }




</style>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TICKET</title>
</head>
<body>
<div class="ticket">
    <div class="centered" ><img  src="{{ asset('images/logonegro.jpg') }}" alt="Logo"></div>
    <p class="izquierdazo" style="font-size: 11px">
        <br>Barrio las Flores 4ta calle Pte. (calle las parejas) entre 3ra y 5ta av. sur, Metapan, Santa Ana
        <br>TELEFONO: 7879-4994
        <br>TICKET NUMERO: {{ $infoOrden->id }}
    </p>
    <table width="90%">
        <thead>
        <tr>
            <th class="quantity" style="font-size: 13px !important;">Cant</th>
            <th class="description" style="font-size: 13px !important;">Producto</th>
            <th class="price" style="font-size: 13px !important; text-align: right">Precio</th>
            <th class="price" style="font-size: 13px !important; text-align: right">Total</th>
        </tr>
        </thead>
        <tbody>


        @foreach($lista as $dato)

            <tr>
                <td class="quantity" style="font-size: 15px !important;">{{ $dato->cantidad }}</td>
                <td class="description" style="font-size: 13px !important; ">{{ $dato->nomproducto }}</td>
                <td class="price" style="font-size: 12px !important; text-align: right;">{{ $dato->precio }}</td>
                <td class="price" style="font-size: 12px !important; text-align: right">{{ $dato->multiplicado }}</td>
            </tr>


        @endforeach

        <tr>
            <td class="quantity" style="font-size: 15px !important;"></td>
            <td class="description" style="font-size: 13px !important; ">TOTAL</td>
            <td class="price" style="font-size: 12px !important; text-align: right;"></td>
            <td class="price" style="font-size: 12px !important; text-align: right">${{ $suma }}</td>
        </tr>

        </tbody>
    </table>

    <p class="izquierdazo" style="margin-top: 0px !important; font-size: 14px !important;">

        <br>Fecha: {{ $fecha }}
        <br>Cliente: {{ $infoDireccion->nombre }}
        <br>Direccion: {{ $infoDireccion->direccion }}</p>

    <p class="centered" style="margin-top: 25px !important; font-size: 13px !important;">GRACIAS POR SU COMPRA
        <br>EL TUNCAZO</p>
</div>
</body>
</html>
