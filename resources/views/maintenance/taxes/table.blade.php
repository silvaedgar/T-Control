<tr class="{{ !$item->activo ? 'bg-danger' : '' }}">
    <td> {{ $loop->iteration }} </td>
    <td> {{ $item->percent }}
        <x-badge-null :item="$item->activo" message=""></x-badge-null>
    </td>
    <td> {{ $item->description }} </td>
    <td>
        <x-actions-table-component url="maintenance.taxes" :item="$item" messageToolTip="Impuesto" messageDeleteInput="al Impuesto:">
        </x-actions-table-component>
    </td>
</tr>
