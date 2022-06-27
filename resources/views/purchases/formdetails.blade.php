@if (!isset($invoice))
    <div class="row mt-md-2">
        <input type="hidden" class="form-control" id="ptax" step="any">
        <input type="hidden" class="form-control" id="ptax_id" step="any">
        {{-- Use 2 coins, File config not yet active here show 2 price --}}
        <div class="col-sm-6 col-md-3 col-lg-3 col-xl-4">
            <label for="validationCustom01" class="form-label">Producto</label>
            {{-- <span>Producto</span> --}}
        </div>
        <div class="col-sm-2 col-md-1 col-lg-1">
            <label for="validationCustom02" class="form-label">Ctdad</label>
        </div>
        <div class="col-sm-2 col-lg-1">
            <label for="validationCustom02" class="form-label">Precio</label>
        </div>
        <div class="col-xs-4 col-sm-1 col-lg-1">
            <label for="validationCustom02" class="form-label"> Subtotal </label>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-3 col-lg-3 col-xl-4">
            <select name="pidproduct" id="pidproduct" class="form-control" onchange="SearchProductPrice('Purchase')">
                <option value=0> Seleccione un Producto ...</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}"> {{ $product->name }} </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2 col-md-1 col-lg-1">
            <input type="number" class="form-control" style="margin-top: -5px" id="pcantidad" step="any"
                onkeyup="CalcSubtotal()">
        </div>
        <div class="col-sm-2 col-lg-1">
            <input type="number" class="form-control" style="margin-top: -5px" onkeyup="CalcSubtotal()" id="pprecio"
                step="any">
            {{-- @if ($base_coin->id != 1) aqui va coin_id como lo capturo ?? --}}
            <input type="number" class="form-control" style="margin-top: -5px" onkeyup="CalcSubtotal()"
                id="pprecioother" step="any">
            {{-- @endif --}}
        </div>
        <div class="col-xs-4 col-sm-1 col-lg-1">
            <label for="validationCustom02" id="psubtotal" class="form-label"> </label>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2 float-end mt-md-2 mt-lg-4 mt-sm-3 ml-xl-2">
            <input type="button" value="Agrega Producto" onclick="AddItem()" id="additem" class="btn-sm btn-primary">
        </div>
        <div class="col-xs-6 col-sm-6 col-md-2 col-lg-2 mt-md-2 mt-lg-4 mt-sm-3 ml-md-5">
            <button type="submit" class="btn-sm btn-danger">{{ __('Grabar') }}</button>
        </div>
    </div>
@endif
<br>
<div style="height: 16.5rem; overflow: scroll">
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
                        {{ isset($invoice_details) ? count($invoice_details) : '' }} </span> </th>
            </tr>
        </thead>
        <tbody>
            @if (isset($invoice_details))
                @foreach ($invoice_details as $detail)
                    <tr style="font-size:small ; background: white; text-align: left">
                        <td> {{ $detail->item }}</td>
                        <td> {{ $detail->name }}</td>
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
