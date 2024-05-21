<style>


    * {
        font-family: 'Times New Roman';
        margin-left: 75px !important;
        margin-top: 0px !important;
        margin-right: 0px !important;
    }

    td,
    th,
    tr,
    table {
        margin-left: 20px !important;
        border-top: 1px solid black;
        border-collapse: collapse;
        width: 90%;
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
        font-weight: bold;
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


    <br><br> <br><br>

    <p class="d" style="margin-top: 8px !important;  line-height: 2;  font-weight: bold; font-size: 16px">


        <br> {{ $infoDireccion->nombre }}
        <br>{{ $fecha }}

</p>


<br>

    <table >
        <thead>
        <tr>
            <th style="font-size: 16px !important; font-weight: bold">CANTIDAD</th>
            <th style="font-size: 16px !important; font-weight: bold">PRODUCTO</th>

        </tr>
        </thead>
        <tbody>


        @foreach($lista as $dato)

            <tr>
                <td class="quantity" style="font-size: 20px !important; font-weight: bold">{{ $dato->cantidad }}</td>
                <td class="description" style="font-size: 18px !important; font-weight: bold">{{ $dato->nomproducto }}</td>
            </tr>

        @endforeach

        <br><br>

        <tr>
            <td class="quantity" style="font-size: 15px !important;"></td>
            <td class="description" style="font-size: 16px !important; font-weight: bold">TOTAL</td>
            <td class="price" style="font-size: 12px !important; text-align: right;"></td>
            <td class="price" style="font-size: 16px !important; font-weight: bold; text-align: right">${{ $suma }}</td>
        </tr>

        </tbody>
    </table>


    <p class="izquierdazo" style="margin-top: 16px !important; font-size: 17px !important;">

        <br>Direccion: {{ $infoDireccion->direccion }}

         @if($infoDireccion->punto_referencia != null)

            <br>
            <br>Referencia: {{ $infoDireccion->punto_referencia }}

         @endif

    </p>


</div>
</body>
</html>
