<tr class="{{ !$item->activo ? 'bg-danger' : '' }}">

    <td> {{ $loop->iteration }} </td>
    <td> {{ $item->document_type }}-{{ $item->document }} </td>
    <td> {{ $item->name }}
        <x-badge-null :item="$item->activo" message=""></x-badge-null>
    </td>
    <td> {{ $item->contact }}</td>

    <td> {{ $item->balance }} {{ $config['data']['calcCoin']->symbol }}
    </td>
    <td>
        <x-actions-table-component url="suppliers" :item="$item" messageToolTip="Proveedor" messageDeleteInput="Proveedor:" :otherButtons="[['url' => 'balance', 'messageToolTip' => 'Ver Movimientos', 'icon' => 'fa fa-money']]">
        </x-actions-table-component>
    </td>
</tr>
