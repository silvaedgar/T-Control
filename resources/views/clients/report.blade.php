<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>T-Control (Listado de Clientes con Deudas)</title>
    <style>
        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }

        th {
          border: 1px solid #dddddd;
          text-align: center;
        }

        tr:nth-child(even) {
          background-color: #dddddd;
        }
        </style>

</head>
<body>
  <h3>Listado  de Clientes con Deuda al {{ date("d-m-Y",strtotime(now())) }} </h3>
  @if ($base_coins->id != 1)
    <h4> Tasa de Cambio del Dia: {{ $tasa }} </h4>
  @endif

    <table>
        <thead>
            <th>Renglon</th>
            <th>Nombre</th>
            <th>Saldo</th>
            @if ($base_coins->id != 1)
                <th> Saldo Bs. </th>
            @endif
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
             @foreach ($clients as $client)
                    <tr>
                        <td style="width: 13%; text-align:center "> {{$loop->iteration}} </td>
                        <td style="width: 55%; text-align:center" > {{ $client->names }} </td>
                        <td style = "text-align: left "> {{ ($client->count_in_bs == 'S' ? number_format($client->balance / $tasa,2) : $client->balance) }} {{ $base_coins->symbol }} </td>
                        @if ($base_coins->id != 1)
                            <td style = "text-align: left "> {{ ($client->count_in_bs == 'S' ? $client->balance : number_format($client->balance * $tasa,2)) }} BsD. </td>
                        @endif
                    </tr>
                    @php
                        if ($client->count_in_bs == 'S')
                            $total += $client->balance / $tasa;
                        else
                            $total += $client->balance;
                    @endphp
             @endforeach
        </tbody>
        <br>
    </table>
    <span> Monto Total deudores: {{ number_format($total,2) }} {{ $base_coins->symbol }}</span>
</body>
</html>
