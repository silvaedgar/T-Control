<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>T-Control (Listado de Proveedores con Deudas)</title>
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
    <h3>Listado de Deudas a Proveedores al {{ date('d-m-Y', strtotime(now())) }} </h3>
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
            @foreach ($suppliers as $supplier)
                <tr>
                    <td style="text-align: center"> {{ $loop->iteration }} </td>
                    <td style="text-align: center"> {{ $supplier->name }} </td>
                    <td> {{ number_format($supplier->balance, 2) }} $ </td>
                </tr>
                @php
                    $total += $supplier->balance;
                @endphp
            @endforeach
        </tbody>
    </table>
    <span> Total Monto Adeudado: {{ number_format($total, 2) }} $ </span>
</body>

</html>
