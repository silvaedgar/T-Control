<tr
    class="@switch($item->status)
        @case('Anulado')
        bg-danger
        @break
        @default
                                                            ''
    @endswitch">
    <td> {{ $loop->iteration }} </td>
    <td> <a href="" style="cursor: pointer; text-color: black; font-size: 12px"
            title="Saldo Cliente: {{ number_format($item->Client->balance, 2) }} {{ $config['data']['calcCoin']->symbol }} -
            {{ number_format($item->Client->balance * $config['data']['calcCoin']->rate, 2) }} {{ $config['data']['baseCoin']->symbol }}">
            {{ $item->client->names }}
    </td>
    <td> {{ date('d-m-Y', strtotime($item->payment_date)) }} </td>
    <td> {{ $item->mount }} ({{ $item->Coin->symbol }})
    </td>
    <td> <a href="" style="cursor: pointer; text-color: black; font-size: 12px"
            title="Saldo Cliente: {{ number_format($item->Client->balance, 2) }} {{ $config['data']['calcCoin']->symbol }} -
            {{ number_format($item->Client->balance * $config['data']['calcCoin']->rate, 2) }} {{ $config['data']['baseCoin']->symbol }}">
            {{ $item->PaymentForm->description }}
            <x-badge-null :item="$item->status == 'Procesado'" :message="$item->status != 'Procesado' ? 'Pago ' . $item->status : ''"></x-badge-null>

    </td>
    <td> <a href="{{ route('paymentclients.show', $item->id) }}">
            <button class="btn-info" data-bs-toggle="tooltip" title="Ver Pago">
                <i class="fa fa-table" aria-hidden="true"></i> </button>
        </a>
        <input type="hidden" id="message-item-delete" value=" Anular el Pago ">
        @if ($item->status == 'Procesado')
            <form action="{{ route('paymentclients.destroy', $item->id) }}" method="post"
                class="d-inline delete-item">
                @csrf
                @method('delete')
                <button class="btn-danger" data-bs-toggle="tooltip" title="Anular Pago">
                    <i class="fa fa-trash-o" aria-hidden="true"></i></button>
            </form>
        @endif
        <a href="{{ route('clients.balance', $item->client_id) }}">
            <button class="btn-primary" data-bs-toggle="tooltip" title="Ver Movimientos">
                <i class="fa fa-money" aria-hidden="true"></i>
            </button> </a>

    </td>
</tr>
