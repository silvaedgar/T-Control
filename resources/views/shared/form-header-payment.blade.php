<div class="row">
    @php
        $calc_coin_id = $data_common['calc_coin_id'];
        $controller = $data_common['controller'];
        $option = $controller == 'PaymentClient' || $controller == 'PaymentSupplier' ? 'Payment' : '';
    @endphp
    {{-- <input type="hidden" value="{{ isset($payment) ? old('user_id', $payment->user_id) : auth()->user()->id }}"
                name="user_id"> --}}
    <input type="hidden" value="{{ auth()->user()->id }}" name="user_id">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-2 col-md-1 col-lg-1 mt-md-2">
                <label for="" class="form-label">
                    {{ $controller == 'PaymentSupplier' ? 'Proveedor' : 'Cliente' }} </label>
            </div>
            <div class="col-sm-10 col-md-3 col-lg-3 mt-md-2 ml-lg-3">
                @if ($controller == 'PaymentSupplier')
                    <select name="supplier_id" id="supplier_id" class="form-control"
                        onchange="SearchPurchaseSuppliers()" {{ isset($data['invoice']) ? 'disabled' : '' }}>
                        <option value=0> Seleccione un Proveedor ... </option>
                        @foreach ($data['suppliers'] as $supplier)
                            @php
                                $selected = isset($data['invoice']) ? true : false;
                                $selected = (old('supplier_id') > 0 && old('supplier_id') == $supplier->id) || $selected ? true : false;
                            @endphp
                            <option value="{{ $supplier->id }}" {{ $selected ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('supplier_id'))
                        <span id="supplier_id-error" class="error text-danger"
                            for="input-supplier_id">{{ $errors->first('supplier_id') }}</span>
                    @endif
                @else
                    <select name="client_id" id="client_id" class="form-control" onchange="SearchSaleClients()"
                        {{ isset($data['invoice']) ? 'disabled' : '' }}>
                        <option value=0> Seleccione un Proveedor ... </option>
                        @foreach ($data['clients'] as $client)
                            @php
                                $selected = isset($data['invoice']) ? true : false;
                                $selected = (old('client_id') > 0 && old('client_id') == $client->id) || $selected ? true : false;
                            @endphp
                            <option value="{{ $client->id }}" {{ $selected ? 'selected' : '' }}>
                                {{ $client->names }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('client_id'))
                        <span id="client_id-error" class="error text-danger"
                            for="input-client_id">{{ $errors->first('client_id') }}</span>
                    @endif
                @endif
            </div>
            <div class="col-sm-1 col-md-1 mt-sm-3 ml-md-3 ml-xl-3">
                <label for="" class="form-label">Fecha</label>
            </div>
            <div class="col-sm-4 col-md-2 col-lg-2 col-xl-2 mt-sm-1 ml-sm-2" style="margin-top: -8px">
                <input type="date" name="payment_date" id="payment_date" class="form-control"
                    {{ isset($data['invoice']) ? 'disabled' : '' }}
                    value="{{ old('payment_date', isset($data['invoice']) ? $data['invoice']->payment_date : date('Y-m-d')) }}">
                @if ($errors->has('payment_date'))
                    <span id="payment_date-error" class="error text-danger"
                        for="input-payment_date">{{ $errors->first('payment_date') }}</span>
                @endif
            </div>
            <div class="col-sm-3 col-md-2 col-lg-2 col-xl-2 mt-sm-3 ml-md-3">
                <label for="" class="form-label">Forma de Pago</label>
            </div>
            <div class="col-sm-3 col-md-2 col-lg-2 mt-sm-2" style="margin-top: -8px">
                <select name="payment_form_id" id="payment_form_id" class="form-select"
                    {{ isset($data['invoice']) ? 'disabled' : '' }}>
                    <option value=0> Seleccione Forma de Pago ... </option>
                    @foreach ($data['payment_forms'] as $payment_form)
                        @php
                            $selected = isset($data['invoice']) ? true : false;
                            // if ($controller == 'PaymentSupplier') {
                            //     $selected = isset($payment) && $payment->payment_form_id == $payment_form->id ? true : false;
                            // } else {
                            //     $selected = isset($payment) && $payment->payment_form_id == $payment_form->id ? true : false;
                            // }
                            $selected = (old('payment_form_id') > 0 && old('payment_form_id') == $payment_form->id) || $selected ? true : false;
                        @endphp
                        <option value="{{ $payment_form->id }}" {{ $selected ? 'selected' : '' }}>
                            {{ $payment_form->description }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('payment_form_id'))
                    <span id="payment_form_id-error" class="error text-danger"
                        for="input-payment_form_id">{{ $errors->first('payment_form_id') }}</span>
                @endif
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-1 col-md-1">
                <label for="" class="form-label">Moneda</label>
            </div>
            <div class="col-sm-3 col-md-2  ml-sm-3" style="margin-top: -13px">
                <select name="coin_id" id="coin_id" class="form-control"
                    onchange="SearchCoinBase('{{ $controller }}','{{ $option }}')"
                    {{ isset($data['invoice']) ? 'disabled' : '' }}>
                    @foreach ($data['coins'] as $coin)
                        <option value="{{ $coin->id }}"
                            @php $selected = isset($data['invoice']) ? true : false;
                                $selected = old('coin_id') == 0 && $calc_coin_id == $coin->id && !$selected ? true : false;
                                // $selected = (isset($invoice) && $invoice->coin_id == $coin->id && old('coin_id') == 0) || $selected ? true : false;
                                $selected = (old('coin_id') > 0 && old('coin_id') == $coin->id) || $selected ? true : false; @endphp
                            {{ $selected ? 'selected' : '' }}>
                            {{ $coin->name }} - {{ $coin->symbol }} - {{ $coin->id }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('coin_id'))
                    <span id="coin_id-error" class="error text-danger"
                        for="input-coin_id">{{ $errors->first('coin_id') }}</span>
                @endif
            </div>
            <div class="col-sm-2 col-md-1" style="margin-top: -8px">
                <input type="number" name="rate_exchange" id="rate_exchange" class="form-control"
                    onkeyup="CalculateMountOtherCoin()" step="any" {{ isset($data['invoice']) ? 'disabled' : '' }}
                    value="{{ old('rate_exchange', isset($data['invoice']) ? $data['invoice']->rate_exchange : 1) }}">
                @if ($errors->has('rate_exchange'))
                    <span id="rate_exchange-error" class="error text-danger"
                        for="input-rate_exchange">{{ $errors->first('rate_exchange') }}</span>
                @endif
            </div>
            <div class="col-sm-1 col-md-1">
                <label for="" class="form-label">Monto</label>
            </div>
            <div class="col-sm-3 col-md-2 ml-sm-2" style="margin-top: -8px">
                <input type="number" name="mount" id="mount" class="form-control" step="any"
                    value="{{ old('mount', isset($data['invoice']) ? $data['invoice']->mount : 0) }}"
                    {{ isset($data['invoice']) ? 'disabled' : '' }} onkeyup="CalculateMountOtherCoin()" />
                @if ($errors->has('mount'))
                    <span id="mount-error" class="error text-danger"
                        for="input-mount">{{ $errors->first('mount') }}</span>
                @endif
            </div>
            {{-- <div class="col-sm-2 col-md-2 mt-sm-3"> --}}
            <div class="col-sm-2 col-md-2 ">

                <label for="" class="form-label">Observaciones</label>
            </div>
            {{-- <div class="col-sm-6 col-md-2 mt-sm-1 ml-sm-2" style="margin-top: -30px"> --}}
            <div class="col-sm-6 col-md-2 ml-sm-2" style="margin-top: -8px">

                <input type="text" name="observations" id="observations" class="form-control"
                    value="{{ old('observations', isset($data['invoice']) ? $data['invoice']->observations : '') }}"
                    {{ isset($data['invoice']) ? 'disabled' : '' }} />
            </div>
        </div>

        {{-- El div de las dos filas abiertas se cierran en el details --}}
