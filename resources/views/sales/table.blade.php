<tr
    class="@switch($item->status)
                @case('Anulada')
                    bg-danger
                    text-white
                    @break
                @case('Historico')
                    bg-warning
                    @break
                @default ''
        @endswitch">

    <td> {{ $loop->iteration }} </td>
    <td> <a href="" class="{{ $item->status == 'Anulada' ? 'text-white' : '' }}"
            style="cursor: pointer; font-size: 12px"
            title="Saldo Cliente: {{ number_format($item->Client->balance, 2) }} {{ $config['data']['calcCoin']->symbol }} -
            {{ number_format($item->Client->balance * $config['data']['calcCoin']->rate, 2) }} {{ $config['data']['baseCoin']->symbol }}">
            {{ $item->client->names }}
        </a>

    </td>
    <td> {{ date('d-m-Y', strtotime($item->sale_date)) }} </td>
    <td> {{ $item->mount }}({{ $item->coin->symbol }}) </td>
    <td> <a href="" class="{{ $item->status == 'Anulada' ? 'text-white' : '' }}"
            style="cursor: pointer; text-color: black; font-size: 12px"
            title="Saldo Cliente: {{ number_format($item->Client->balance, 2) }} {{ $config['data']['calcCoin']->symbol }} -
            {{ number_format($item->Client->balance * $config['data']['calcCoin']->rate, 2) }} {{ $config['data']['baseCoin']->symbol }}">
            {{ $item->mount - $item->paid_mount }}({{ $item->coin->symbol }})
        </a>
        <x-badge-null :item="$item->status == 'Pendiente' || $item->status == 'Parcial'" :message="$item->status != 'Pendiente' && $item->status != 'Parcial'
            ? ($item->status == 'Cancelada'
                ? 'Pagada'
                : 'Venta ' . $item->status)
            : ''"></x-badge-null>

    </td>
    {{-- <td> {{ $item->status == 'Parcial' ? 'Parcialmente Cancelada' : $item->status }}
    </td> --}}
    <td>
        {{-- <x-actions-table-component url="clients" :item="$item" messageToolTip="Cliente" messageDeleteInput="Cliente:" :otherButtons="[['url' => 'balance', 'messageToolTip' => 'Ver Movimientos', 'icon' => 'fa fa-money']]">
    </x-actions-table-component> --}}

        <a href="{{ route('sales.show', $item) }}">
            <button class="btn-info" data-bs-toggle="tooltip" title="Ver Factura">
                <i class="fa fa-table" aria-hidden="true"></i> </button> </a>
        <input type="hidden" id="message-item-delete" value=" Anular la Factura ">
        @if ($item->status == 'Parcial' || $item->status == 'Pendiente')
            <form action="{{ route('sales.destroy', $item->id) }}" method="post" class="d-inline delete-item">
                @csrf
                @method('delete')
                <button class="btn-danger" data-bs-toggle="tooltip" title="Anular Factura">
                    <i class="fa fa-trash-o" aria-hidden="true"></i></button>
            </form>
        @endif
        <a href="{{ route('clients.balance', $item->client_id) }}">
            <button class="btn-primary" data-bs-toggle="tooltip" title="Ver Movimientos">
                <i class="fa fa-money" aria-hidden="true"></i>
            </button> </a>
    </td>
</tr>
