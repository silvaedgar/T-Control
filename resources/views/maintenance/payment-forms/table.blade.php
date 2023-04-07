<tr class="{{ !$item->activo ? 'bg-danger' : '' }}">
    <td> {{ $loop->iteration }} </td>
    <td> {{ $item->payment_form }} </td>
    <td> {{ $item->description }}
        <x-badge-null :item="$item->activo" message=""></x-badge-null>
    </td>
    <td>
        <x-actions-table-component url="maintenance.paymentforms" :item="$item" messageToolTip="Forma de Pago" messageDeleteInput="Forma de Pago:">
        </x-actions-table-component>
    </td>
</tr>
