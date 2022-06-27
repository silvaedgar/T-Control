<div class="table-responsive">
    <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
        <thead class=" text-primary">
            <th>Item</th>
            <th>Fecha</th>
            <th>Monto</th>
            <th>Abono</th>
            <th>Saldo</th>
            <th></th>
        </thead>
        <tbody>
            @php
                $balance = $movements[0]->balance;
            @endphp
            @if ($movements[0]->type != 'Balance')
                @foreach ($movements as $movement)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ date('d-m-Y', strtotime($movement->date)) }} </td>
                        <td>
                            @if ($movement->type == 'Compras')
                                @if ($movement->count_in_bs == 'N' || !isset($movement->count_in_bs))
                                    @php
                                        $mount = $movement->mount_balance;
                                    @endphp
                                    @if ($movement->coin_id == $data_common['calc_coin_id'])
                                        {{ number_format($movement->mount, 2) }} ({{ $movement->symbol }})
                                        @if ($data_common['controller'] == 'Client')
                                            {{-- Este if es un parche por lo del rate_exchange de payment supplier en 1 no muestra bien los datos por eso se filtro para no mostrarse --}}
                                            {{ $movement->coin_id != $data_common['base_coin_id']
                                                ? ' - ' .
                                                    number_format($movement->mount * $movement->rate_exchange, 2) .
                                                    '(' .
                                                    $data_common['base_coin_symbol'] .
                                                    ')'
                                                : '' }}
                                        @endif
                                    @else
                                        {{ number_format($movement->mount / $movement->rate_exchange, 2) }}
                                        ({{ $data_common['calc_coin_symbol'] }}) -
                                        {{ number_format($movement->mount, 2) }}
                                        ({{ $movement->coin_id == $data_common['base_coin_id'] ? $data_common['base_coin_symbol'] : $movement->symbol }})
                                    @endif
                                @else
                                    {{-- Cuenta en Bs caso Livia y Paola --}}
                                    @php
                                        $mount = $movement->mount;
                                    @endphp
                                    @if ($movement->coin_id == $data_common['calc_coin_id'])
                                        {{ number_format($movement->mount, 2) }} ({{ $movement->symbol }})
                                        @if ($movement->coin_id != $data_common['base_coin_id'])
                                            @php
                                                $mount = $movement->mount * $movement->rate_exchange;
                                            @endphp
                                            {{-- // La moneda es diferenete al Bs y a la de calculo --}}
                                            - {{ number_format($movement->mount * $movement->rate_exchange, 2) }}
                                            ({{ $data_common['base_coin_symbol'] }})
                                        @endif
                                    @else
                                        @if ($movement->coin_id == $data_common['base_coin_id'])
                                            {{ number_format($movement->mount, 2) }} ({{ $movement->symbol }})
                                        @else
                                            {{-- si llega aqui la moneda no es $ ni Bs --}}
                                            @php
                                                $mount = $movement->mount * $movement->rate_exchange;
                                            @endphp
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td>
                            @if ($movement->type == 'Pagos')
                                @if ($movement->count_in_bs == 'N' || !isset($movement->count_in_bs))
                                    @php
                                        $mount = $movement->mount_balance;
                                    @endphp
                                    @if ($movement->coin_id == $data_common['calc_coin_id'])
                                        {{ number_format($movement->mount, 2) }} ({{ $movement->symbol }})
                                        {{-- @if ($data_common['controller'] == 'Client')
                            Este if es un parche por lo del rate_exchange de payment supplier en 1 no muestra bien los datos por eso se filtro para no mostrarse
                                            {{ $movement->coin_id != $data_common['base_coin_id']
                                                ? ' - ' .
                                                    number_format($movement->mount * $movement->rate_exchange, 2) .
                                                    '(' .
                                                    $data_common['base_coin_symbol'] .
                                                    ')'
                                                : '' }}
                                        @endif --}}
                                    @else
                                        {{-- sino es moneda de calculo muestra la conversion y despues el monto --}}
                                        {{ number_format($movement->mount / $movement->rate_exchange, 2) }}
                                        ({{ $data_common['calc_coin_symbol'] }}) -
                                        {{ number_format($movement->mount, 2) }}
                                        ({{ $movement->coin_id == $data_common['base_coin_id'] ? $data_common['base_coin_symbol'] : $movement->symbol }})
                                    @endif
                                @else
                                    {{-- Cuenta en Bs caso Livia y Paola --}}
                                    @if ($movement->coin_id == $data_common['calc_coin_id'])
                                        @php
                                            $mount = $movement->mount;
                                        @endphp
                                        {{ number_format($movement->mount, 2) }} ({{ $movement->symbol }})
                                        @if ($movement->coin_id != $data_common['base_coin_id'])
                                            {{-- // La moneda es diferenete al Bs y a la de calculo --}}
                                            - {{ number_format($movement->mount * $movement->rate_exchange, 2) }}
                                            ({{ $data_common['base_coin_symbol'] }})
                                        @endif
                                    @else
                                        {{ number_format($movement->mount, 2) }} ({{ $movement->symbol }})
                                        @php
                                            $mount = $movement->mount;
                                        @endphp
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td>
                            {{ number_format($balance, 2) }}
                            {{ $movement->count_in_bs == 'N' ? $data_common['calc_coin_symbol'] : $data_common['base_coin_symbol'] }}
                            @php
                                $balance = $movement->type == 'Pagos' ? $balance + $mount : $balance - $mount;
                            @endphp
                        </td>
                        <td>
                            @if ($movement->type == 'Compras')
                                <a
                                    href="{{ $data_common['controller'] == 'Client'
                                        ? route('sales.show', $movement->id)
                                        : route('purchases.show', $movement->id) }}">
                                    <button class="btn-info" data-bs-toggle="tooltip" title="Ver Detalle">
                                        <i class="fa fa-money" aria-hidden="true"></i>
                                    </button> </a>
                            @else
                                <a
                                    href="{{ $data_common['controller'] == 'Client'
                                        ? route('paymentclients.show', $movement->id)
                                        : route('paymentsuppliers.show', $movement->id) }}">
                                    <button class="btn-info" data-bs-toggle="tooltip" title="Ver Detalle">
                                        <i class="fa fa-money" aria-hidden="true"></i>
                                    </button> </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif
            {{-- Fila del Balance Inicial en caso de ser diferemte de 0 --}}
            @if ($balance != 0)
                <tr>
                    <td> {{ count($movements) == 1 ? 1 : count($movements) + 1 }} </td>
                    <td> Balance Inicial </td>
                    <td> {{ $movements[0]->name }} </td>
                    <td> </td>
                    <td> {{ number_format($balance, 2) }} ($) </td>
                    <td> </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
