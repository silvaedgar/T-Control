<div class="card mt-0 ml-sm-0" style="font-size: 11px">
    <div class="card-body mx-auto">
        <div class="row mx-auto">
            <h5 class="card-title mx-auto font-bold ">Ultimas Compras del Producto</h5>

        </div>
    </div>
    <div class="card-text w-75 mx-auto h-auto " >
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
                @foreach ($config['data']['purchases'] as $detail)
                    <tr>
                        <td> {{ date('d-m-Y', strtotime($detail->purchase->purchase_date)) }} </td>
                        <td> {{ $detail->purchase->Supplier->name }} </td>
                        <td> {{ $detail->quantity }} </td>
                        <td> {{ $detail->Purchase->Coin->symbol == '$' ? number_format($detail->price, 3) : number_format($detail->price, 2) }}
                            ({{ $detail->Purchase->Coin->symbol }})
                        </td>
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
