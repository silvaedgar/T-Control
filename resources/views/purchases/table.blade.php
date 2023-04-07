<tr
    class="@switch($item->status)
                @case('Anulada')
                    bg-danger
                    text-white
                    @break
                @default ''
        @endswitch">

    <td> {{ $loop->iteration }} </td>
    <td> <a href="" class="{{ $item->status == 'Anulada' ? 'text-white' : '' }} "
            style="cursor: pointer; font-size: 12px"
            title="Saldo Proveedor: {{ number_format($item->Supplier->balance, 2) }} {{ $config['data']['calcCoin']->symbol }} ">
            {{ $item->Supplier->name }}
        </a>
    </td>
    <td> {{ date('d-m-Y', strtotime($item->purchase_date)) }} </td>
    <td> {{ $item->mount }}({{ $item->Coin->symbol }}) </td>
    <td>
        <a href="" class="{{ $item->status == 'Anulada' ? 'text-white' : '' }} "
            style="cursor: pointer;  font-size: 12px"
            title="Saldo Proveedor: {{ number_format($item->Supplier->balance, 2) }} {{ $item->Coin->symbol }}">
            {{ $item->mount - $item->paid_mount }}({{ $item->Coin->symbol }})
            <x-badge-null :item="$item->status == 'Pendiente' || $item->status == 'Parcial'" :message="$item->status != 'Pendiente' && $item->status != 'Parcial'
                ? ($item->status == 'Cancelada'
                    ? 'Pagada'
                    : 'Compra ' . $item->status)
                : ''"></x-badge-null>
        </a>
    </td>
    {{-- <td> {{ $item->status == 'Parcial' ? 'Parcialmente Cancelada' : $item->status }}
    </td> --}}
    <td>
        <a href="{{ route('purchases.show', $item->id) }}">
            <button class="btn-info" data-bs-toggle="tooltip" title="Ver Factura">
                <i class="fa fa-table" aria-hidden="true"></i> </button> </a>
        <input type="hidden" id="message-item-delete" value=" Anular la Factura ">
        @if ($item->status == 'Parcial' || $item->status == 'Pendiente')
            <form action="{{ route('purchases.destroy', $item->id) }}" method="post" class="d-inline delete-item">
                @csrf
                @method('delete')
                <button class="btn-danger" data-bs-toggle="tooltip" title="Anular Factura">
                    <i class="fa fa-trash-o" aria-hidden="true"></i></button>
            </form>
        @endif
        <a href="{{ route('suppliers.balance', $item->supplier_id) }}">
            <button class="btn-primary" data-bs-toggle="tooltip" title="Ver Movimientos">
                <i class="fa fa-money" aria-hidden="true"></i>
            </button> </a>
    </td>




    {{-- <td> {{ $loop->iteration }} </td>
<td> {{ $item->code }} </td>
<td> {{ $item->name }}
    <x-badge-null :item="$item->activo" message=""></x-badge-null>
</td>
<td> {{ $item->ProductCategory->ProductGroup->description }} </td>
<td> {{ $item->ProductCategory->description }} </td>

<td> {{ $item->sale_price }} {{ $dataCommon['sale_coin_symbol'] }}
<td> {{ $item->cost_price }} {{ $dataCommon['purchase_coin_symbol'] }}
</td>
<td>
    <x-actions-table-component url="products" :item="$item" messageToolTip="Producto" messageDeleteInput="Producto:">
    </x-actions-table-component> --}}
    {{-- Poniendo other buttons puedo ver las compras y ventas del producto considerarla con el tiempo --}}
    {{-- </td> --}}
