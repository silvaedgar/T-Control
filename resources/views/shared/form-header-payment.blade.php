<div class="row">
    <div class="col-sm-12">
        {{-- fila de proveedor-cliente fecha forma de pago --}}
        <div class="row">
            <div class="col-sm-2 col-md-1 mt-md-3">
                <label for="" class="form-label"> {{ $config['var_header']['labelCaption'] }}
                </label>
            </div>
            {{-- Proveedor-cliente --}}
            <div class="col-sm-10 col-md-10 col-lg-2 col-xl-3 mt-2">
                @if (isset($invoice))
                    <input type="text" value="{{ $config['var_header']['name'] }}" class="form-control" />
                @else
                    @php
                        $variable = $config['var_header']['id'];
                    @endphp
                    <select name="{{ $config['var_header']['id'] }}" id="{{ $config['var_header']['id'] }}"
                        class="form-select select2" {{ isset($invoice) ? '' : '' }}
                        onchange="SearchIdPayment('{{ $config['var_header']['id'] }}')">
                        <option value=0> Seleccione un {{ $config['var_header']['labelCaption'] }} ... </option>
                        @foreach ($config['var_header']['table'] as $table)
                            <option value="{{ $table->id }}"
                                {{ old($config['var_header']['id']) == $table->id || (isset($invoice) && $invoice->$variable == $table->id) ? 'selected' : '' }}>
                                {{ isset($table->names) ? $table->names : $table->name }}
                            </option>
                        @endforeach
                    </select>
                @endif
                <x-message-error :hasError="$errors->has($config['var_header']['id'])" :message="$errors->first($config['var_header']['id'])"></x-message-error>
            </div>
            <div class="col-sm-2 col-md-1 ml-md-3 ml-xl-3 mt-sm-3">
                <label for="" class="form-label">Fecha: </label>
            </div>
            {{-- Fecha --}}
            <div class="col-sm-3 col-md-2 col-lg-2 ml-sm-2 mt-sm-1">
                <input type="date" name="payment_date" id="payment_date" class="form-control"
                    {{ isset($invoice) ? 'disabled' : '' }}
                    value="{{ old('payment_date', isset($invoice) ? $invoice->payment_date : date('Y-m-d')) }}">
                <x-message-error :hasError="$errors->has('payment_date')" :message="$errors->first('payment_date')"> </x-message-error>
            </div>
            <div class="col-sm-3 col-md-2 col-lg-3 col-xl-2 mt-sm-3 ml-md-3">
                <label for="" class="form-label">Forma de Pago</label>
            </div>
            {{-- Forma de Pago --}}
            <div class="col-sm-3 col-md-2 col-lg-2 mt-1">
                @if (isset($invoice))
                    <input value=" {{ $invoice->paymentForm->description }}" type="text" class="form-control"
                        disabled />
                @else
                    <select name="payment_form_id" id="payment_form_id" class="form-select"
                        {{ isset($invoice) ? 'disabled' : '' }}>
                        <option value=0> Seleccione Forma de Pago ... </option>
                        @foreach ($config['data']['paymentForms'] as $payment_form)
                            <option value="{{ $payment_form->id }}"
                                {{ old('payment_form_id') == $payment_form->id || (isset($invoice) && $invoice->payment_form_id == $payment_form->id) ? 'selected' : '' }}>
                                {{ $payment_form->description }}
                            </option>
                        @endforeach
                    </select>
                @endif
                <x-message-error :hasError="$errors->has('payment_form_id')" :message="$errors->first('payment_form_id')"> </x-message-error>
            </div>
        </div>
        {{-- 2da fila --}}
        <div class="row">
            <div class="col-sm-1 col-md-1 mt-3">
                <label for="" class="form-label">Moneda: </label>
            </div>
            {{-- MOneda --}}
            <div class="col-sm-3 col-md-2 ml-sm-1">
                @if (isset($invoice))
                    <input type="text" value="{{ $invoice->coin->symbol }}" id="coin_id" disabled
                        class="form-control" />
                @else
                    <select name="coin_id" id="coin_id" class="form-control" onchange="ChangeCoin('Payment')"
                        {{ isset($invoice) ? 'disabled' : '' }}>
                        @foreach ($config['data']['coins'] as $coin)
                            <option value="{{ $coin->id }}" {{ old('coin_id') == $coin->id ? 'selected' : '' }}>
                                {{ $coin->name }}
                            </option>
                        @endforeach
                    </select>
                @endif
                <x-message-error :hasError="$errors->has('coin_id')" :message="$errors->first('coin_id')"> </x-message-error>
            </div>
            <div class="col-sm-2 col-md-1 mt-md-1">
                <input type="number" name="rate_exchange" id="rate_exchange" class="form-control"
                    onfocus="JumpRateExchange()" onkeyup="CalculateOtherMount()" step="any"
                    {{ isset($invoice) ? 'disabled' : '' }}
                    value="{{ old('rate_exchange', isset($invoice) ? $invoice->rate_exchange : 1) }}">
                <x-message-error :hasError="$errors->has('rate_exchange')" :message="$errors->first('rate_exchange')"> </x-message-error>
            </div>
            <div class="col-sm-1 col-md-1 mt-3">
                <label for="" class="form-label">Monto</label>
            </div>
            <div class="col-sm-3 col-md-2 ml-sm-2 mt-1">
                <input type="number" name="mount" id="mount" class="form-control" step="any"
                    value="{{ old('mount', isset($invoice) ? $invoice->mount : 0) }}"
                    {{ isset($invoice) ? 'disabled' : '' }} onkeyup="CalculateMountOtherCoin()" />
                <x-message-error :hasError="$errors->has('mount')" :message="$errors->first('mount')"> </x-message-error>
            </div>
            <div class="col-sm-2 col-md-2 mt-3">
                <label for="" class="form-label">Observaciones</label>
            </div>
            <div class="col-sm-6 col-md-2 ml-sm-2">
                <input type="text" name="observations" id="observations" class="form-control"
                    value="{{ old('observations', isset($invoice) ? $invoice->observations : '') }}"
                    {{ isset($invoice) ? 'disabled' : '' }} />
            </div>
        </div>
    </div>
</div>
