@if (!isset($sale))
    <div class="row mt-2">
        <div class="col-sm-6 col-xl-4">
            <label for="validationCustom01" class="form-label">Producto</label>
            <select name="pidproduct" id="pidproduct" class="form-control" onchange="SearchProductPrice('Venta')">
                <option value=0> Seleccione un Producto ...</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}"> {{ $product->name }} </option>
                @endforeach
            </select>
        </div>
        <input type="hidden" class="form-control" id="ptax" step="any">
        <input type="hidden" class="form-control" id="ptax_id" step="any">

        <div class="col-sm-2 col-xl-1">
            <label for="validationCustom02" class="form-label">Cantidad</label>
            <input type="number" class="form-control" id="pcantidad" step="any" onkeyup="CalcSubtotal()">
        </div>
        <div class=" col-sm-2 col-xl-1">
            <label for="validationCustom02" class="form-label">Precio</label>
            <input type="number" class="form-control" onkeyup="CalcSubtotal()" id="pprecio" step="any">
        </div>
        <div class=" col-sm-2 col-xl-2">
            <label for="validationCustom02" class="form-label"> Subtotal </label>
            <label for="validationCustom02" id="psubtotal" class="form-label"> </label>
        </div>
        <div class="card-footer ml-0 mr-auto col-sm-2 col-xl-2">
            <input type="button" value="Agregar Producto" onclick="AddItem()" id="additem" class="btn-sm  btn-primary">
        </div>
        <div class="card-footer ml-auto mr-0  col-sm-6 col-xl-2">
            <button type="submit" class="btn-sm btn-danger">{{ __('Grabar Factura') }}</button>
        </div>
    </div>
@endif
<br />
<div style="height: 16.5rem; overflow:auto">
    <table class="table-sm table-hover table-responsive-sm tblscroll table-bordered table-striped" id="details-table">
        <thead style="background: rgb(202, 202, 236); text-align: center; ">
            <tr>
                <th style="width: 3%"> Item </td>
                <th style="width: 30%">Producto</th>
                <th style="width: 10%">Cantidad</th>
                <th style="width: 12%">Precio</th>
                <th style="width: 12%">Impuesto</th>
                <th style="width: 13%">Subtotal</th>
                <th style="width: 15%">Renglones: <span id="totalrenglones"></span> </th>
            </tr>
        </thead>
        <tbody style="text-align:left ; font-size: small; background: blue" name="carrito">
            @if (isset($sale_details))
                @foreach ($sale_details as $detail)
                    <tr style="font-size: smaller; background: white; text-align: left">
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
