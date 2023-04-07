<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>T-Control (Listado de Proveedores con Deudas)</title>
    {{-- <style>
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
    </style> --}}

</head>

<body>
    <h3>Listado de Ventas </h3>
    <table>
        <thead>
            <th>Renglon</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Saldo Pendiente</th>
        </thead>
        <tbody>
            @foreach ($config['data']['collection'] as $item)
                <tr>
                    <td> {{ $loop->iteration }} </td>
                    <td> {{ $item->client->names }} </td>
                    <td> {{ date('d-m-Y', strtotime($item->sale_date)) }} </td>
                    <td> {{ number_format($item->mount, 2) }}({{ $item->coin->symbol }}) </td>
                    <td> {{ number_format($item->mount - $item->paid_mount, 2) }}({{ $item->coin->symbol }})</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
