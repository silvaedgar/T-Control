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

@php
    $filter = (isset($filter) ? $filter : []);
    $mensaje = "Status: Todos. ";
    $status = 'Todos';
    $datestart = "";
    $dateend =  "";
    if (isset($filter)) { //existe la variable filter verifica los mismos
      foreach ($filter as $key => $value) {
        switch ($filter[$key][1]) {
            case '=':  // es status
                $status = $filter[$key][2];
                break;
            case '>=': // dia de inicio
                $datestart = $filter[$key][2];
                $mensaje .= " Fecha Inicial: ".date('d-m-Y',strtotime($datestart));
                break;
            case '<=': // dia de inicio
                $dateend = $filter[$key][2];
                $mensaje .= " Fecha Final: ".date('d-m-Y',strtotime($dateend));
                break;
        }
      }
    }
@endphp

     <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Facturas de Ventas</h5>
                <p class="card-text">{{$mensaje}}</p>
            </div>
        </div>
        <table class="table-sm table-hover table-striped"  id="data-table" style="width: 100%">
                <thead class=" text-primary">
                    <th>Item</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Saldo Pendiente</th>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr >
                            <td> {{ $loop->iteration }}  </td>
                            <td> {{ date("d-m-Y",strtotime($sale->sale_date)) }} </td>
                            <td> {{ $sale->Client->names }} </td>
                            <td> {{ $sale->mount }}({{$sale->Coin->symbol}}) </td>
                            <td> {{ $sale->mount - $sale->paid_mount }}({{$sale->Coin->symbol}}) </td>
                       </tr>
                    @endforeach
                </tbody>
        </table>
  </div>
</body>
</html>
