<div class="row">
    <input type="hidden" value ="{{ auth()->user()->id }}" name = "user_id">
    <div class="col-sm-12 ">
        <div class="row">
            <div class="col-sm-2 col-xl-1">
                <label for="" class="form-label">Cliente</label>
            </div>
            <div class="col-xl-5 col-sm-10">
                <select name="client_id" id="client_id" class="form-control"
                    {{(isset($sale) ? 'disabled' : '') }} >
                    <option value= 0> Seleccione un Cliente ... </option>
                    @foreach ($clients as $client)
                        <option
                            @if (isset($sale))
                                @if ($sale->client_id == $client->id)
                                    selected
                                @endif
                            @endif
                        value="{{ $client->id}}"> {{ $client->names}} </option>
                    @endforeach
                </select>
                @if ($errors->has('client_id'))
                    <span id="client_id-error" class="error text-danger" for="input-client_id">{{ $errors->first('client_id') }}</span>
                @endif
            </div>
            <div class="col-sm-1">
                <label for="" class="form-label">Fecha</label>
            </div>
            <div class="col-xl-2 col-sm-5">
                <input type="date" name="sale_date" id="sale_date" class="form-control"
                    value = "{{ old('sale_date', (isset($sale) ? $sale->sale_date :'')) }}">
                @if ($errors->has('sale_date'))
                    <span id="sale_date-error" class="error text-danger" for="input-sale_date">{{ $errors->first('sale_date') }}</span>
                @endif
            </div>
            <div class="col-sm-2 col-xl-1">
                <label for="" class="form-label">Factura</label>
            </div>
            <div class="col-xl-2 col-sm-4">
                <input type="text" name="invoice" id="invoice" class="form-control"
                 value = "{{ old('invoice', (isset($sale) ? $sale->invoice :'')) }}">

                {{-- {!! Form::text('invoice', isset($sales) ? $sale->invoice : '',
                ['class'=>'form-control']) !!} --}}
            </div>
        </div>
        <div class="row mt-1">
            <div class="col-sm-3 col-md-1">
                <label for="" class="form-label">Moneda</label>
            </div>
            <div class="col-md-2 col-xl-1 col-sm-4">
                <select name="coin_id" id="coin_id" class="form-control" onchange="SearchCoinBase('Venta')"
                             {{(isset($sale) ? 'disabled' : '') }} >
                </select>
                @if ($errors->has('coin_id'))
                    <span id="coin_id-error" class="error text-danger" for="input-coin_id">{{ $errors->first('coin_id') }}</span>
                @endif

            </div>
            <div class="col-xl-2 col-sm-3">
                <input type="number" name="rate_exchange" id="rate_exchange" class="form-control"
                        step="any" value = "{{ old('rate_exchange', (isset($sale) ? $sale->rate_exchange :'')) }}">
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
                        value = "{{ old('observations', (isset($sale) ? $sale->observations :'')) }}">
            </div>
        </div>

    </div>
</div>

