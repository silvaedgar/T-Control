<div class="row">
    <input type="hidden" value ="0" id = "sales_client">
    <input type="hidden" value ="{{isset($client) ? old('user_id',$paymentform->user_id) : auth()->user()->id }}" name = "user_id">

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-2 col-md-2 col-xl-1">
                <label for="" class="form-label">Cliente</label>
            </div>
            <div class="col-xl-4 col-sm-10 col-md-6">
                <select name="client_id" id="client_id" class="form-control"
                            onchange="SearchSaleClients()" >
                    <option value= 0> Seleccione un Cliente ... </option>
                    @foreach ($clients as $client)
                        <option
                            @if (old('client_id') == $client->id)
                                selected
                            @endif
                            value="{{ $client->id}}"> {{ $client->names}}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('client_id'))
                    <span id="client_id-error" class="error text-danger" for="input-client_id">{{ $errors->first('client_id') }}</span>
                @endif
            </div>
            <div class="col-sm-2 col-md-1">
                <label for="" class="form-label">Fecha</label>
            </div>
            <div class="col-xl-2 col-sm-4 col-md-3">
                <input type="date" name="payment_date" id="payment_date" class="form-control"
                    value = "{{ old('payment_date', (isset($sale) ? $sale->payment_date : date('Y-m-d'))) }}">
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
                <select name="coin_id" id="coin_id" class="form-control" onchange="SearchCoinBase('Venta')" >
                </select>
                @if ($errors->has('coin_id'))
                    <span id="coin_id-error" class="error text-danger" for="input-coin_id">{{ $errors->first('coin_id') }}</span>
                @endif

            </div>
            <div class="col-md-2 col-sm-2">
                <input type="number" name="rate_exchange" id="rate_exchange" class="form-control"
                onchange = "CalculateMountOtherCoin()" step="any"
                value = "{{ old('rate_exchange', (isset($sale) ? $sale->rate_exchange :'')) }}">
                @if ($errors->has('rate_exchange'))
                    <span id="rate_exchange-error" class="error text-danger" for="input-rate_exchange">{{ $errors->first('rate_exchange') }}</span>
                @endif

            </div>
             <div class="col-sm-1">
                <label for="" class="form-label">Monto</label>
            </div>
            <div class="col-sm-3 col-md-2">
                <input type="number" name="mount" id="mount" class="form-control"
                        step="any" onchange = "CalculateMountOtherCoin()">
                @if ($errors->has('mount'))
                    <span id="mount-error" class="error text-danger" for="input-mount">{{ $errors->first('mount') }}</span>
                @endif
            </div>
             <div class="col-md-2 col-sm-3">
                <label for="" class="form-label">Observaciones</label>
            </div>
            <div class="col-md-2 col-sm-6">
                <input type="text" name="observations" id="observations" class="form-control"
                        value = "{{ old('observations', (isset($sale) ? $sale->observations :'')) }}">
            </div>
        </div>

    </div>

</div>

