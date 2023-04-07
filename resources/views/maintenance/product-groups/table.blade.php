<tr class="{{ !$item->activo ? 'bg-danger' : '' }}">
    <td> {{ $loop->iteration }} </td>
    <td> {{ $item->description }}
        <x-badge-null :item="$item->activo" message=""></x-badge-null>
    </td>
    <td>
        <x-actions-table-component url="maintenance.productgroups" :item="$item" messageToolTip="Grupo de Producto"
            messageDeleteInput="al Grupo de Producto:">
        </x-actions-table-component>
    </td>
</tr>
