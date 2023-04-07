<tr class="{{ !$item->activo ? 'bg-danger' : '' }}">
    <td> {{ $loop->iteration }} </td>
    <td> {{ $item->productGroup->description }} </td>
    <td> {{ $item->description }}
        <x-badge-null :item="$item->activo" message=""></x-badge-null>
    </td>
    <td>
        <x-actions-table-component url="maintenance.productcategories" :item="$item" messageToolTip="Categoria de Producto"
            messageDeleteInput="{{ $item->activo ? 'Eliminar' : 'Restaurar' }} a la Categoria de Producto: {{ $item->description }}">
        </x-actions-table-component>
    </td>
</tr>
