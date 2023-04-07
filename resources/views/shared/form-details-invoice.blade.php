@if (!isset($invoice))
    <input type="hidden" class="form-control" id="ptax" step="any">
    <input type="hidden" class="form-control" id="ptax_id" step="any">
    {{-- Use 2 coins, File config not yet active here show 2 price --}}
    <div class="row">
        {{--  Producto  --}}
        <div class="col-sm-5 col-md-3 col-lg-4  mb-3">
            <label for="validationCustom01" class="form-label">Producto</label>
            <select name="pidproduct" id="pidproduct" class="form-control select2 mt-0"
                onchange="ProductPrice('{{ $config['var_header']['labelCaption'] }}',event)">
                {{-- onchange="SearchProductPrice('{{ $type_operation }}')"> --}}
                <option value=0> Seleccione un Producto ...</option>
                @foreach ($config['data']['products'] as $product)
                    <option value="{{ $product->id }}"> {{ $product->name }} </option>
                @endforeach
            </select>
        </div>
        {{--  Cantidad  --}}
        <div class="col-sm-2 col-md-1 col-lg-1 mb-3">
            <div class="col-sm-2 col-md-1 col-lg-1">
                <label for="validationCustom02" class="form-label">Ctdad</label>
            </div>
            <input type="text" class="form-control" style="margin-top: -5px" id="productQty" step="any"
                onkeyup="CalcSubTotal('Qty',event) ">
        </div>
        {{--  Precio  --}}
        <div class="col-12 col-sm-3 col-md-2 col-lg-1 mb-4">
            <div class="col-3 col-md-2 col-lg-1">
                <label for="validationCustom02" class="form-label">Precio </label>
            </div>
            <input type="text" class="form-control" style="margin-top: -5px" id="productPrice" step="any"
                onkeyup="CalcSubTotal('Price', event)">
            <input type="{{ $config['data']['calcCoin']->id == $config['data']['baseCoin']->id ? 'hidden' : 'text' }}"
                class="form-control" style="margin-top: -5px" id="productPriceOther" step="any"
                onkeyup="CalcSubTotal('PriceOther', event)">
        </div>
        {{--  Subtotal  --}}
        <div class="col-sm-2 col-md-1 col-lg-1">
            <div class="col-1 col-sm-2 col-md-1 col-lg-1">
                <label for="validationCustom02" class="form-label"> Subtotal </label>
            </div>

            <label for="validationCustom02" id="productSubTotal" class="form-label"> </label>
        </div>
        {{--  Buttons  --}}
        <div class="col-sm-6 col-md-2 col-lg-4 ms-lg-4  mt-sm-3 ms-md-2  mt-md-3 mt-lg-4 ml-xl-2 mb-5">
            <input type="button" value="Agrega Producto" onclick="AddItem()" id="additem" class="btn-sm btn-primary">
            <button type="submit" class="btn-sm btn-danger">{{ __('Grabar') }}</button>
        </div>
        {{--  <div class="col-sm-6 col-md-2 col-lg-1 col-xl-1 mt-sm-3 mt-md-3 mt-lg-4 ml-md-5 me-lg-3 mb-5">
        </div>  --}}
    </div>
@endif
<br>
<div style="max-height: 16.5rem; overflow: scroll; {{ isset($invoice) ? 'margin-top: -15px' : 'margin-top: -50px' }} ">
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
                        {{ isset($invoice) ? count(isset($invoice->PurchaseDetails) ? $invoice->PurchaseDetails : $invoice->SaleDetails) : '' }}
                    </span> </th>
            </tr>
        </thead>
        <tbody>
            @if (isset($invoice))
                @foreach (isset($invoice->PurchaseDetails) ? $invoice->PurchaseDetails : $invoice->SaleDetails as $detail)
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
