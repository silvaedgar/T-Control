<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>T-Control (Pagos de Clientes)</title>
</head>
<body>




@php
    $filter = (isset($filter) ? $filter : []);
    $mensaje = "";
    $status = 'Todos';
    $datestart = "";
    $dateend =  "";
    if (isset($filter)) { //existe la variable filter verifica los mismos
      foreach ($filter as $key => $value) {
        switch ($filter[$key][1]) {
            case '=':  // es status
                $status = $filter[$key][2];
                $mensaje .= "Status: ".$status;
                break;
            case '>=': // dia de inicio
                $datestart = $filter[$key][2];
                $mensaje .= ". Fecha de Inicio: ".date('d-m-Y',strtotime($datestart));

                break;
            case '<=': // dia de inicio
                $dateend = $filter[$key][2];
                $mensaje .= ". Fecha Final: ".date('d-m-Y',strtotime($dateend));

                break;
        }
      }
    }
@endphp

<div class="container">
    <div class="card">
            <h5 class="card-title">Pagos Efectuados por Clientes</h5>
            <p class="card-text">{{$mensaje}}</p>
    </div>
    <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
        <thead class=" text-primary">
                    <th>Item</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Forma de Pago</th>
        </thead>
        <tbody>
                    @foreach ($paymentclients as $paymentclient)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $paymentclient->Client->names }} </td>
                        <td> {{ date("d-m-Y",strtotime($paymentclient->payment_date)) }} </td>
                        <td> {{ $paymentclient->mount }} ({{$paymentclient->Coin->symbol }}) </td>
                        <td> {{ $paymentclient->PaymentForm->description }} </td>
                   </tr>
                    @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
