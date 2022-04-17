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
    <table>
        <thead>
            <th>Renglon</th>
            <th>Nombre</th>
            <th>Saldo</th>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
             @foreach ($clients as $client)
                    <tr>
                        <td style="width: 15%; text-align:center "> {{$loop->iteration}} </td>
                        <td style="width: 60%; text-align:center" > {{ $client->names }} </td>
                        <td style = "text-align: left "> {{ $client->balance }}BsD </td>
                    </tr>
                    @php
                        $total += $client->balance;
                    @endphp
             @endforeach
        </tbody>
        <br>
    </table>
    <span> Monto Total deudores: {{ $total }} BsD</span>
</body>
</html>
