<div class="row">
    <input type="hidden" value ="{{ auth()->user()->id }}" name = "user_id">
    <div class="col-sm-12 ">
        <div class="row">
            <div class="col-sm-2 col-xl-1">
                <label for="" class="form-label">Proveedor</label>
            </div>
            <div class="col-xl-5 col-sm-10">
                <select name="supplier_id" id="supplier_id" class="form-control"
                    {{(isset($purchase) ? 'disabled' : '') }} >
                    <option value= 0> Seleccione un Proveedor ... </option>
                    @foreach ($suppliers as $supplier)
                        <option
                            @if (isset($purchase))
                                @if ($purchase->supplier_id == $supplier->id)
                                    selected
                                @endif
                            @endif
                        value="{{ $supplier->id}}"> {{ $supplier->name}} </option>
                    @endforeach
                </select>
                @if ($errors->has('supplier_id'))
                    <span id="supplier_id-error" class="error text-danger" for="input-supplier_id">{{ $errors->first('supplier_id') }}</span>
                @endif
            </div>
            <div class="col-sm-1">
                <label for="" class="form-label">Fecha</label>
            </div>
            <div class="col-xl-2 col-sm-5">
                <input type="datetime" name="purchase_date" id="purchase_date" class="form-control"
                    value = "{{ old('purchase_date', (isset($purchase) ? $purchase->purchase_date :'')) }}">
                @if ($errors->has('purchase_date'))
                    <span id="purchase_date-error" class="error text-danger" for="input-purchase_date">{{ $errors->first('purchase_date') }}</span>
                @endif
            </div>
            <div class="col-sm-2 col-xl-1">
                <label for="" class="form-label">Factura</label>
            </div>
            <div class="col-xl-2 col-sm-4">
                <input type="text" name="invoice" id="invoice" class="form-control"
                 value = "{{ old('invoice', (isset($purchase) ? $purchase->invoice :'')) }}">

                {{-- {!! Form::text('invoice', isset($purchases) ? $purchase->invoice : '',
                ['class'=>'form-control']) !!} --}}
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-sm-3 col-md-1">
                <label for="" class="form-label">Moneda</label>
            </div>
            <div class="col-md-2 col-xl-1 col-sm-4">
                <select name="coin_id" id="coin_id" class="form-control" onchange="SearchCoinBase('Compra')"
                             {{(isset($purchase) ? 'disabled' : '') }} >
                </select>
                @if ($errors->has('coin_id'))
                    <span id="coin_id-error" class="error text-danger" for="input-coin_id">{{ $errors->first('coin_id') }}</span>
                @endif

            </div>
            <div class="col-xl-2 col-sm-3">
                <input type="number" name="rate_exchange" id="rate_exchange" class="form-control" onchange="SearchCoinBase('Compra')"
                        step="any" value = "{{ old('rate_exchange', (isset($purchase) ? $purchase->rate_exchange :'')) }}">
                @if ($errors->has('rate_exchange'))
                    <span id="rate_exchange-error" class="error text-danger" for="input-rate_exchange">{{ $errors->first('rate_exchange') }}</span>
                @endif

            </div>
            <div class="col-sm-3 col-md-2 col-xl-1">
                <label for="" class="form-label">Condiciones</label>
            </div>
            <div class="col-sm-4  col-xl-2">
                <input type="radio" name="conditions" value='Credito' checked> Credito
                <input type="radio" name="conditions" value='Contado'> Contado
            </div>
            <div class="col-sm-1">
                <label for="" class="form-label">Notas</label>
            </div>
            <div class="col-sm-4 col-md-10 col-xl-3">
                <input type="text" name="observations" id="observations" class="form-control"
                        value = "{{ old('observations', (isset($purchase) ? $purchase->observations :'')) }}">
            </div>
        </div>

    </div>
</div>

