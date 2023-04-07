<tr
    class="@switch($item->status)
        @case('Anulado')
            bg-danger
            @break
        @default
            ''
    @endswitch">
    {{-- <tr class="font-extrabold" style="cursor: pointer"> --}}
    <td> {{ $loop->iteration }} </td>
    <td> <a href="" style="cursor: pointer; text-color: black; font-size: 12px"
            title="Saldo Proveedor: {{ number_format($item->Supplier->balance, 2) }} {{ $config['data']['calcCoin']->symbol }} ">
            {{ $item->Supplier->name }}
        </a>
    </td>
    <td> {{ date('d-m-Y', strtotime($item->payment_date)) }} </td>
    <td> <a href="" style="cursor: pointer; text-color: black; font-size: 12px"
            title="Saldo Proveedor: {{ number_format($item->Supplier->balance, 2) }} {{ $item->Coin->symbol }}">
            {{ $item->mount }}
            ({{ $item->Coin->symbol }})
        </a>

    </td>
    <td> <a href="" style="cursor: pointer; text-color: black; font-size: 12px"
            title="Saldo Proveedor: {{ number_format($item->Supplier->balance, 2) }} {{ $item->Coin->symbol }}">
            {{ $item->PaymentForm->description }}
            <x-badge-null :item="$item->status == 'Procesado'" :message="$item->status != 'Procesado' ? 'Pago ' . $item->status : ''"></x-badge-null>
        </a>

    </td>
    <td> <a href="{{ route('paymentsuppliers.show', $item->id) }}">
            <button class="btn-info" data-bs-toggle="tooltip" title="Ver Pago">
                <i class="fa fa-table" aria-hidden="true"></i> </button>
        </a>
        @if ($item->status == 'Procesado')
            <input type="hidden" id="message-item-delete" value=" Anular el Pago ">
            <form action="{{ route('paymentsuppliers.destroy', $item->id) }}" method="post"
                class="d-inline delete-item">
                @csrf
                @method('delete')
                <button class="btn-danger" data-bs-toggle="tooltip" title="Anular Pago">
                    <i class="fa fa-trash-o" aria-hidden="true"></i></button>
            </form>
        @endif
        <a href="{{ route('suppliers.balance', $item->supplier_id) }}">
            <button class="btn-primary" data-bs-toggle="tooltip" title="Ver Movimientos">
                <i class="fa fa-money" aria-hidden="true"></i>
            </button> </a>
    </td>
</tr>
