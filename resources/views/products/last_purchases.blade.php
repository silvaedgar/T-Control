<div class="card mt-0 ml-sm-0" style="font-size: 11px">
    <div class="card-body mx-auto">
        <div class="row mx-auto">
            <h5 class="card-title mx-auto font-bold ">Ultimas Compras del Producto</h5>

        </div>
    </div>
    <div class="card-text">
        <table class="table" style="width:100%">
            <thead>
                <tr style="background-color: #dbd7d7">
                    <th class="" style="font-size: 11px">Fecha</th>
                    <th style="font-size: 11px">Proveedor</th>
                    <th style="font-size: 11px">Cantidad</th>
                    <th style="font-size: 11px">Precio</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cont = 1;
                @endphp
                @foreach ($data['purchases'] as $purchase)
                    <tr>
                        <td> {{ date('d-m-Y', strtotime($purchase->purchase_date)) }} </td>
                        <td> {{ $purchase->Supplier->name }} </td>
                        @foreach ($purchase->PurchaseDetails as $item)
                            @if ($item->product_id == $data['product']->id)
                                <td> {{ $item->quantity }} </td>
                                <td> {{ $purchase->Coin->symbol == '$' ? number_format($item->price, 3) : number_format($item->price, 2) }}
                                    ({{ $purchase->Coin->symbol }})
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    @php
                        $cont++;
                    @endphp
                    @if ($cont > 5)
                    @break
                @endif
            @endforeach
        </tbody>
    </table>

</div>
</div>
