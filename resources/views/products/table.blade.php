<tr class="{{ !$item->activo ? 'bg-danger' : '' }}">
    <td> {{ $loop->iteration }} </td>
    <td> {{ $item->code }} </td>
    <td> {{ $item->name }}
        <x-badge-null :item="$item->activo" message=""></x-badge-null>
    </td>
    <td> {{ $item->ProductCategory->ProductGroup->description }} </td>
    <td> {{ $item->ProductCategory->description }} </td>

    <td> {{ $item->sale_price }} {{ $config['data']['coinSale']->symbol }} </td>
    <td> {{ $item->cost_price }} {{ $config['data']['coinPurchase']->symbol }}
    </td>
    <td>
        <x-actions-table-component url="products" :item="$item" messageToolTip="Producto"
            messageDeleteInput="Producto:">
        </x-actions-table-component>
        {{-- Poniendo other buttons puedo ver las compras y ventas del producto considerarla con el tiempo --}}
    </td>
</tr>
