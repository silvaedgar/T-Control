@if (!isset($invoice))
    <input type="hidden" class="form-control" id="ptax" step="any">
    <input type="hidden" class="form-control" id="ptax_id" step="any">
    {{-- Use 2 coins, File config not yet active here show 2 price --}}
    <div class="row mt-1 mb-5 ">
        {{--  Producto  --}}
        <div class="col-12 col-md-5 col-xl-3 ">
            <div class="row">
                <div class="col-3 col-md-8 col-xl-2 text-right align-items-center">
                    <label for="validationCustom01" class="form-label">Producto</label>
                </div>
                <div class="col col-md-12 col-xl-12 text-md-center">
                    <select name="pidproduct" id="pidproduct" class="form-control select2 w-100 "
                        onchange="ProductPrice('{{ $config['var_header']['labelCaption'] }}',event)">
                        {{-- onchange="SearchProductPrice('{{ $type_operation }}')"> --}}
                        <option value=""> Seleccione un Producto ...</option>
                        @foreach ($config['data']['products'] as $product)
                            <option value="{{ $product->id }}"> {{ $product->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        {{--  Cantidad  --}}
        <div class="col-4 col-md-2 col-xl-2 ">
            <div class="row ">
                <div class="col-3 mt-1  ">
                    <label for="validationCustom02" class="form-label">Ctdad</label>
                </div>
                <div class="col-9 ">
                    <input type="text" class="form-control pl-xl-2" id="productQty" step="any"
                        onkeyup="CalcSubTotal('Qty',event) ">
                </div>
            </div>
        </div>
        {{--  Precio  --}}
        <div class="col-5 col-md-3 col-xl-2 p-0 ">
            <div class="row">
                <div class="col-3 mt-1">
                    <label for="validationCustom02" class="form-label">Precio </label>
                </div>
                <div class="col-9">
                    <input type="text" class="form-control" id="productPrice" step="any"
                        onkeyup="CalcSubTotal('Price', event)">
                </div>
                <div class="col-3">
                </div>

                <div class="col-9">
                    <input
                        type="{{ $config['data']['calcCoin']->id == $config['data']['baseCoin']->id ? 'hidden' : 'text' }}"
                        class="form-control" id="productPriceOther" step="any"
                        onkeyup="CalcSubTotal('PriceOther', event)">
                </div>
            </div>
        </div>
        {{--  Subtotal  --}}
        <div class="col-3 col-md-2 col-xl-2 ">
            <div class="row">
                <div class="col-12 text-left mt-1">
                    <label for="validationCustom02" class="form-label col-12"> Subtotal </label>
                </div>
                <div class="col-12 text-center">
                    <label for="validationCustom02" id="productSubTotal" class="form-label text-center"> </label>
                </div>

            </div>
        </div>
        {{--  Buttons  --}}
        <div class="col-12 col-xl-3 p-xl-1 mt-2">
            <div class="row g-2">
                <div class="col-6 col-xl-7 text-end">
                    <input type="button" value="Agrega Producto" onclick="AddItem()" id="additem"
                        class="btn-sm btn-primary  px-1 w-100">
                </div>
                <div class="col-6 col-xl-5">
                    <button type="submit" class="btn-sm btn-danger w-100 px-1">{{ __('Grabar') }}</button>
                </div>
            </div>
        </div>
    </div>
@endif
<br>

<div style="max-height: 16.5rem; overflow: scroll; {{ isset($invoice) ? 'margin-top: -15px' : 'margin-top: -50px' }} ">
    <x-message-error :hasError="$errors->has('mount')" :message="$errors->first('mount')" class="mx-auto"> </x-message-error>
    <table class="table-sm table-hover table-responsive-sm tblscroll table-bordered table-striped" id="details-table">
        <thead style="background: rgb(202, 202, 236); text-align: center; ">
            <tr>
                <th style="width: 3%"> Item </td>
                <th style="width: 35%">Producto</th>
                <th style="width: 10%">Cantidad</th>
                <th style="width: 12%">Precio</th>
                <th style="width: 12%">Impuesto</th>
                <th style="width: 13%">Subtotal</th>
                <th style="width: 10%">Renglones: <span id="totalrenglones">
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
