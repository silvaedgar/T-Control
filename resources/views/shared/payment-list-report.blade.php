<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>T-Control Reporte {{ $data_common['header'] }} </title>
</head>

<body>
    <div class="card">
        <h5 class="card-title"> {{ $data_common['header'] }} </h5>
        <p class="card-text">{{ $data_common['data_filter']['message'] }}</p>
    </div>
    <table class="table-sm table-hover table-striped" id="data-table"
        style="width: 125%; margin-left: -15px; font-size:11px; ">
        <thead>
            <th style="width: 5%">Item</th>
            <th style="width: 15%">Fecha</th>
            <th style="width: 40%">
                {{ $data_common['controller'] == 'PaymentClient' || $data_common['controller'] == 'Sale' ? 'Cliente' : 'Proveedor' }}
            </th>
            <th style="width: 20%">Monto</th>
            <th style="width: 20%">
                {{ $data_common['controller'] == 'PaymentClient' || $data_common['controller'] == 'PaymentSupplier' ? 'Forma de Pago' : 'Saldo Pendiente' }}
            </th style="width: 5%">
            <th>
            </th>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($models as $payment)
                <tr>
                    <td> {{ $loop->iteration }} </td>
                    @switch($data_common['controller'])
                        @case('Sale')
                            <td> {{ date('d-m-Y', strtotime($payment->sale_date)) }} </td>
                        @break;
                        @case('Purchase')
                            <td> {{ date('d-m-Y', strtotime($payment->purchase_date)) }} </td>
                        @break;

                        @default
                            <td> {{ date('d-m-Y', strtotime($payment->payment_date)) }} </td>
                    @endswitch
                    @if ($data_common['controller'] == 'PaymentClient' || $data_common['controller'] == 'Sale')
                        <td> {{ $payment->Client->names }} </td>
                    @else
                        <td> {{ $payment->Supplier->name }} </td>
                    @endif

                    <td> {{ number_format($payment->mount, 2) }} {{ $payment->Coin->symbol }} </td>
                    @if ($data_common['controller'] == 'PaymentClient' || $data_common['controller'] == 'PaymentSupplier')
                        <td> {{ $payment->PaymentForm->description }} </td>
                    @else
                        <td> {{ number_format($payment->mount - $payment->paid_mount, 2) }}
                            {{ $payment->Coin->symbol }}
                        </td>
                    @endif
                    <td class="text-start">
                        {{ $payment->status == 'Anulado' || $payment->status == 'Anulada' ? $payment->status : '' }}
                    </td>
                </tr>
                @php
                    $total += $payment->mount;
                @endphp
            @endforeach
        </tbody>
    </table>
    <br>
    {{-- <span> Monto Total:  {{ number_format($total,2) }} BsD </span> --}}

</body>

</html>
