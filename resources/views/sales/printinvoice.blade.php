<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>T-Control (Factura Cliente)</title>
</head>
<body>

    <div class="container">
        <div class="row text-center" style="font-size: large; ">
            Venta
        </div>
        <div class="row">
            <div>
                <h6> Cliente: {{$client->names}} {{$client->id }} </h6>
            </div>
            <div>
                <h6> Fecha: {{ date("d-m-Y",strtotime($sale->sale_date))}} </h6>
            </div>
            <div class="col-5">
                <h6> Monto Factura:  {{ $sale->mount }} {{ $sale->simbolo }}</h6>
            </div>
            @if ($sale->observations != '')
            <div class="col-7">
                <h6> {{ $sale->observations }} </h6>
            </div>
            @endif
        </div>
        <table class="table-sm table-hover table-striped"  style="width: 100%">
            <thead>
                <th>Item</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </thead>
            <tbody>
                @foreach ($sale_details as $details)
                    <tr >
                        <td> {{ $loop->iteration }} </a> </td>
                        <td> {{ $details->name}} </td>
                        <td> {{ number_format($details->quantity,2) }} </td>
                        <td> {{ number_format($details->price,2) }} </td>
                        <td> {{ number_format($details->tax + $details->price * $details->quantity,2)  }}</td>
                   </tr>
                @endforeach
            </tbody>
          </table>
    </div>
</body>
</html>
