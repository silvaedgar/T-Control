<div class="table-responsive">
    <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
        <thead class="text-primary">
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
                                    @if ($movement->coin_id == $config['data']['calcCoin']->id)
                                        {{ number_format($movement->mount, 2) }} ({{ $movement->symbol }})
                                    @else
                                        {{ number_format($movement->mount / $movement->rate_exchange, 2) }}
                                        ({{ $config['data']['calcCoin']->symbol }})
                                        -
                                        {{ number_format($movement->mount, 2) }}
                                        ({{ $movement->coin_id == $config['data']['baseCoin']->id ? $config['data']['baseCoin']->symbol : $movement->symbol }})
                                    @endif
                                @else
                                    {{-- Cuenta en Bs caso Livia y Paola --}}
                                    @php
                                        $mount = $movement->mount;
                                    @endphp
                                    @if ($movement->coin_id == $config['data']['calcCoin']->id)
                                        {{ number_format($movement->mount, 2) }} ({{ $movement->symbol }})
                                        @if ($movement->coin_id != $config['data']['baseCoin']->id)
                                            @php
                                                $mount = $movement->mount * $movement->rate_exchange;
                                            @endphp
                                            {{-- // La moneda es diferenete al Bs y a la de calculo --}}
                                            - {{ number_format($movement->mount * $movement->rate_exchange, 2) }}
                                            ({{ $config['data']['baseCoin']->symbol }})
                                        @endif
                                    @else
                                        @if ($movement->coin_id == $config['data']['baseCoin']->id)
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
                                    @if ($movement->coin_id == $config['data']['calcCoin']->id)
                                        {{ number_format($movement->mount, 2) }} ({{ $movement->symbol }})
                                    @else
                                        {{-- sino es moneda de calculo muestra la conversion y despues el monto --}}
                                        {{ number_format($movement->mount / $movement->rate_exchange, 2) }}
                                        ({{ $config['data']['calcCoin']->symbol }}) -
                                        {{ number_format($movement->mount, 2) }}
                                        ({{ $movement->coin_id == $config['data']['baseCoin']->id ? $config['data']['baseCoin']->symbol : $movement->symbol }})
                                    @endif
                                @else
                                    {{-- Cuenta en Bs caso Livia y Paola --}}
                                    @if ($movement->coin_id == $config['data']['calcCoin']->id)
                                        @php
                                            $mount = $movement->mount;
                                        @endphp
                                        {{ number_format($movement->mount, 2) }} ({{ $movement->symbol }})
                                        @if ($movement->coin_id != $config['data']['baseCoin']->id)
                                            {{-- // La moneda es diferenete al Bs y a la de calculo --}}
                                            - {{ number_format($movement->mount * $movement->rate_exchange, 2) }}
                                            ({{ $config['data']['baseCoin']->symbol }})
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
                            {{ $movement->count_in_bs == 'N' ? $config['data']['calcCoin']->symbol : $config['data']['baseCoin']->symbol }}
                            @php
                                $balance = $movement->type == 'Pagos' ? $balance + $mount : $balance - $mount;
                            @endphp
                        </td>
                        <td>
                            <a href="{{ route($movement->route, $movement->id) }}">
                                <button class="btn-info" data-bs-toggle="tooltip" title="Ver Detalle">
                                    <i class="fa fa-money" aria-hidden="true"></i>
                                </button> </a>
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
