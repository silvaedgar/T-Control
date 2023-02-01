@if (!isset($data['invoice']))
    @php
        $base_coin_id = $data_common['base_coin_id'];
        $calc_coin_id = $data_common['calc_coin_id'];
        $type_operation = $data_common['controller'];
    @endphp
    <div class="row mt-md-2">
        <input type="hidden" class="form-control" id="ptax" step="any">
        <input type="hidden" class="form-control" id="ptax_id" step="any">
        {{-- Use 2 coins, File config not yet active here show 2 price --}}
    </div>
    <div class="row">
        <div class="col-sm-5 col-md-3 col-lg-4 col-xl-5 col-xxl-6 mb-3 ">
            {{-- <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1"> --}}
            <label for="validationCustom01" class="form-label">Producto</label>
            {{-- <span>Producto</span> --}}
            {{-- </div> --}}
            {{-- <div class="col-sm-4 col-md-2 col-lg-4 col-xl-4"> --}}
            <select name="pidproduct" id="pidproduct" class="form-control mt-0"
                onchange="SearchProductPrice('{{ $type_operation }}')">
                <option value=0> Seleccione un Producto ...</option>
                @foreach ($data['products'] as $product)
                    <option value="{{ $product->id }}"> {{ $product->name }} </option>
                @endforeach
            </select>
            {{-- </div> --}}
        </div>
        <div class="col-sm-2 col-md-1 col-lg-1 mb-3">
            <div class="col-sm-2 col-md-1 col-lg-1">
                <label for="validationCustom02" class="form-label">Ctdad</label>
            </div>
            <input type="number" class="form-control" style="margin-top: -5px" id="pcantidad" step="any"
                onkeyup="CalcSubtotal('Qty')">
        </div>
        <div class="col-sm-3 col-md-2 col-lg-1 mb-4">
            <div class="col-sm-3 col-md-2 col-lg-1">
                <label for="validationCustom02" class="form-label">Precio</label>
            </div>

            <input type="number" class="form-control" style="margin-top: -5px" onkeyup="CalcSubtotal('Price')"
                id="pprecio" step="any">
            <input type="{{ $calc_coin_id == $base_coin_id ? 'hidden' : 'number' }}" class="form-control"
                style="margin-top: -5px" onkeyup="CalcSubtotal('PriceOther')" id="pprecioother" step="any">
        </div>
        <div class="col-sm-2 col-md-1 col-lg-1 ">
            <div class="col-sm-2 col-md-1 col-lg-1">
                <label for="validationCustom02" class="form-label"> Subtotal </label>
            </div>

            <label for="validationCustom02" id="psubtotal" class="form-label"> </label>
        </div>
        <div class="col-sm-6 col-md-2 col-lg-2 mt-sm-3 mt-md-2 mt-lg-2  ml-xl-2 mb-2">
            <input type="button" value="Agrega Producto" onclick="AddItem()" id="additem" class="btn-sm btn-primary">
        </div>
        <div class="col-sm-6 col-md-2 col-lg-2 col-xl-1 mt-sm-3 mt-md-2 mt-lg-2  ml-md-5 mb-5">
            <button type="submit" class="btn-sm btn-danger">{{ __('Grabar') }}</button>
        </div>
    </div>
@endif
<br>
<div
    style="height: 16.5rem; overflow: scroll; {{ isset($data['invoice']) ? 'margin-top: -15px' : 'margin-top: -50px' }} ">
    <table class="table-sm table-hover table-responsive-sm tblscroll table-bordered table-striped" id="details-table">
        <thead style="background: rgb(202, 202, 236); text-align: center; ">
            <tr>
                <th style="width: 3%"> Item </td>
                <th style="width: 30%">Producto</th>
                <th style="width: 10%">Cantidad</th>
                <th style="width: 12%">Precio</th>
                <th style="width: 12%">Impuesto</th>
                <th style="width: 13%">Subtotal</th>
                <th style="width: 15%">Renglones: <span id="totalrenglones">
                        {{ isset($data['invoice']) ? count(isset($data['invoice']->PurchaseDetails) ? $data['invoice']->PurchaseDetails : $data['invoice']->SaleDetails) : '' }}
                    </span> </th>
            </tr>
        </thead>
        <tbody>
            @if (isset($data['invoice']))
                @foreach (isset($data['invoice']->PurchaseDetails) ? $data['invoice']->PurchaseDetails : $data['invoice']->SaleDetails as $detail)
                    <tr style="font-size:small ; background: white; text-align: left">
                        <td> {{ $detail->item }}</td>
                        <td> {{ $detail->Product->name }}</td>
                        <td> {{ number_format($detail->quantity, 2) }}</td>
                        <td> {{ number_format($detail->price, 2) }}</td>
                        <td> {{ number_format($detail->tax, 2) }}</td>
                        <td> {{ number_format($detail->tax + $detail->price * $detail->quantity, 2) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
