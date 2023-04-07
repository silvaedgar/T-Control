<td> {{ $loop->iteration }} </td>
<td> {{ $item->description }}
    <x-badge-null :item="$item->activo" message="Anulado"></x-badge-null>
</td>
<td> {{ $item->symbol }} </td>
<td>
    <x-actions-table-component url="maintenance.unitmeasures" :item="$item" messageToolTip="Unidad de Medida"
        messageDeleteInput="la Unidad de Medida:">
    </x-actions-table-component>
</td>
