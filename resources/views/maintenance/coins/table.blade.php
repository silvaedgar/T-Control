<tr class="{{ !$item->activo ? 'bg-danger' : '' }}">

    <td style="font-size:10px">
        @if ($item->base_currency == 'S')
            MB
        @endif
        @if ($item->calc_currency_purchase == 'S')
            MCC
        @endif
        @if ($item->calc_currency_sale == 'S')
            MCV
        @endif

    </td>
    <td> {{ $loop->iteration }} </td>
    <td> {{ $item->name }}
        <x-badge-null :item="$item->activo" message=""></x-badge-null>
    </td>
    <td> {{ $item->symbol }} </td>
    <td>
        <x-actions-table-component url="maintenance.coins" :item="$item" messageToolTip="Moneda" messageDeleteInput="Moneda:">
        </x-actions-table-component>
    </td>
</tr>
