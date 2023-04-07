<tr class="{{ !$item->activo ? 'bg-danger' : '' }}">
    <td> {{ $loop->iteration }} </td>
    <td> {{ $item->document_type }}-{{ $item->document }} </td>
    <td> {{ $item->names }}
        <x-badge-null :item="$item->activo" message=""></x-badge-null>
    </td>
    <td> {{ $item->balance }}{{ $item->count_in_bs == 'N' ? $config['data']['calcCoin']->symbol : $config['data']['baseCoin']->symbol }}
        @if (
            $config['data']['calcCoin']->id != $config['data']['baseCoin']->id &&
                $item->count_in_bs == 'N' &&
                $item->balance != 0)
            ({{ number_format($item->balance * $config['data']['calcCoin']->sale_price, 2) }}
            {{ $config['data']['baseCoin']->symbol }})
        @endif
    </td>
    <td>
        <x-actions-table-component url="clients" :item="$item" messageToolTip="Cliente" messageDeleteInput="Cliente:"
            :otherButtons="[['url' => 'balance', 'messageToolTip' => 'Ver Movimientos', 'icon' => 'fa fa-money']]">
        </x-actions-table-component>
    </td>
</tr>
