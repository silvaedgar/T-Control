<div class="row">
    <input type="hidden" value ="0" id = "purchases_supplier">
    <input type="hidden" value ="{{isset($supplier) ? old('user_id',$paymentform->user_id) : auth()->user()->id }}" name = "user_id">

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-2 col-md-2 col-xl-1">
                <label for="" class="form-label">Proveedor</label>
            </div>
            <div class="col-xl-4 col-sm-10 col-md-6">
                <select name="supplier_id" id="supplier_id" class="form-control"
                            onchange="SearchPurchaseSuppliers()" >
                    <option value= 0> Seleccione un Proveedor ... </option>
                    @foreach ($suppliers as $supplier)
                        <option
                            @if (old('supplier_id') == $supplier->id)
                                selected
                            @endif
                            value="{{ $supplier->id}}"> {{ $supplier->name}}
                        </option>
                    @endforeach
                </select>
               @if ($errors->has('supplier_id'))
                    <span id="supplier_id-error" class="error text-danger" for="input-supplier_id">{{ $errors->first('supplier_id') }}</span>
                @endif
            </div>
            <div class="col-sm-2 col-md-1">
                <label for="" class="form-label">Fecha</label>
            </div>
            <div class="col-xl-2 col-sm-4 col-md-3">
                <input type="date" name="payment_date" id="payment_date" class="form-control"
                    value = "{{ old('payment_date', (isset($purchase) ? $purchase->payment_date : date('Y-m-d'))) }}">
                @if ($errors->has('payment_date'))
                    <span id="payment_date-error" class="error text-danger" for="input-payment_date">{{ $errors->first('payment_date') }}</span>
                @endif
            </div>
            <div class="col-xl-2 col-md-2 col-sm-3">
                <label for="" class="form-label">Forma de Pago</label>
            </div>
            <div class="col-xl-2 col-md-9 col-sm-3">
                <select name="payment_form_id" id="payment_form_id" class="form-control">
                    <option value= 0> Seleccione Forma de Pago ... </option>
                    @foreach ($paymentforms as $paymentform)
                        <option
                            @if (old('payment_form_id') == $paymentform->id)
                                selected
                            @endif
                            value="{{ $paymentform->id }}"> {{ $paymentform->payment_form}}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('payment_form_id'))
                    <span id="payment_form_id-error" class="error text-danger" for="input-payment_form_id">{{ $errors->first('payment_form_id') }}</span>
                @endif
            </div>

        </div>
        <div class="row mt-1">
            <div class="col-sm-2 col-md-1">
                <label for="" class="form-label">Moneda</label>
            </div>
            <div class="col-md-2 col-sm-3">
                <select name="coin_id" id="coin_id" class="form-control" onchange="SearchCoinBase('Compra')" >
                </select>
                @if ($errors->has('coin_id'))
                    <span id="coin_id-error" class="error text-danger" for="input-coin_id">{{ $errors->first('coin_id') }}</span>
                @endif

            </div>
            <div class="col-md-2 col-sm-2">
                <input type="number" name="rate_exchange" id="rate_exchange" class="form-control"
                     onchange = "CalculateMountOtherCoin()"
                    step="any" value = "{{ old('rate_exchange', (isset($purchase) ? $purchase->rate_exchange :'')) }}">
                @if ($errors->has('rate_exchange'))
                    <span id="rate_exchange-error" class="error text-danger" for="input-rate_exchange">{{ $errors->first('rate_exchange') }}</span>
                @endif

            </div>
            <div class="col-sm-1">
                <label for="" class="form-label">Monto</label>
            </div>
            <div class="col-sm-3 col-md-2">
                <input type="number" name="mount" id="mount" class="form-control"
                        step="any" onchange="CalculateMountOtherCoin()">
                @if ($errors->has('mount'))
                    <span id="mount-error" class="error text-danger" for="input-mount">{{ $errors->first('mount') }}</span>
                @endif
            </div>
            <div class="col-md-2 col-sm-3">
                <label for="" class="form-label">Observaciones</label>
            </div>
            <div class="col-md-2 col-sm-6">
                <input type="text" name="observations" id="observations" class="form-control"
                        value = "{{ old('observations', (isset($purchase) ? $purchase->observations :'')) }}">
            </div>
        </div>

    </div>

</div>

