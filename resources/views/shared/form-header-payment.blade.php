<div class="container">
    {{-- fila de proveedor-cliente fecha forma de pago --}}
    <div class="row ">
        {{-- Proveedor-cliente --}}
        <div class="col-12 col-md-5">
            <div class="row p-0 align-items-center">
                <div class="col-3 col-md-4 text-right px-1 align-items-center">
                    <span class="form-label "> {{ $config['var_header']['labelCaption'] }}</span>
                </div>
                <div class="col px-1 ml-0">
                    @if (isset($invoice))
                        <input type="text" value="{{ $config['var_header']['name'] }}" class="form-control " />
                    @else
                        @php
                            $variable = $config['var_header']['id'];
                        @endphp
                        <select name="{{ $config['var_header']['id'] }}" id="{{ $config['var_header']['id'] }}"
                            class="select2 w-100" {{ isset($invoice) ? '' : '' }}
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
                </div>
                <x-message-error :hasError="$errors->has($config['var_header']['id'])" :message="$errors->first($config['var_header']['id'])"></x-message-error>
            </div>
        </div>
        {{-- Fecha --}}
        <div class="col-12 mt-3 col-sm-4 col-md-3 mt-md-0  ">
            <div class="row p-0 align-items-center">
                <div class="col-3 px-1 text-right  ">
                    <span class="form-label">Fecha </span>
                </div>
                <div class="col col-sm-9">
                    <input type="date" name="payment_date" id="payment_date" class="form-control py-0 w-sm-50"
                        style="height: 30px" {{ isset($invoice) ? 'disabled' : '' }}
                        value="{{ old('payment_date', isset($invoice) ? $invoice->payment_date : date('Y-m-d')) }}">
                </div>
                <x-message-error :hasError="$errors->has('payment_date')" :message="$errors->first('payment_date')"> </x-message-error>
            </div>
        </div>
        {{-- Forma de Pago --}}
        <div class="col-12 mt-3 col-sm-7 col-md-4 mt-md-0">
            <div class="row p-0 align-items-center">
                <div class="col-3 col-sm-5 text-right px-1 align-items-center ">
                    <span class="form-label "> Forma de Pago</span>
                </div>
                <div class="col col-sm-7 px-1">
                    @if (isset($invoice))
                        <input type="text" value="{{ $config['var_header']['name'] }}" class="form-control " />
                    @else
                        @php
                            $variable = $config['var_header']['id'];
                        @endphp
                        <select name="payment_form_id" id="payment_form_id" class="select2 w-100 "
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
                </div>
                <x-message-error :hasError="$errors->has('payment_form_id')" :message="$errors->first('payment_form_id')"> </x-message-error>
            </div>

        </div>
    </div>

    {{-- 2da fila --}}
    <div class="row mt-3">
        {{-- Moneda y tasa --}}
        <div class="col-12 col-sm-8 col-md-5">
            <div class="row p-0 align-items-center">
                <div class="col-3 text-right px-1 align-items-center ">
                    <span class="form-label">Moneda </span>
                </div>
                {{-- MOneda --}}
                <div class="col px-1 align-items-center ">
                    @if (isset($invoice))
                        <input type="text" value="{{ $invoice->coin->symbol }}" id="coin_id" disabled
                            class="form-control" />
                    @else
                        <select name="coin_id" id="coin_id" class="form-select w-100 py-0 px-2"
                            onchange="ChangeCoin('Payment')" style="height: 30px; font-size:13px"
                            {{ isset($invoice) ? 'disabled' : '' }}>
                            @foreach ($config['data']['coins'] as $coin)
                                <option value="{{ $coin->id }}"
                                    {{ old('coin_id') == $coin->id ? 'selected' : '' }}>
                                    {{ $coin->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    <x-message-error :hasError="$errors->has('coin_id')" :message="$errors->first('coin_id')"> </x-message-error>
                </div>
                <div class="col-3  px-1 align-items-center ">
                    <input type="number" name="rate_exchange" id="rate_exchange" class="form-control"
                        onfocus="JumpRateExchange()" onkeyup="CalculateOtherMount()" step="any"
                        {{ isset($invoice) ? 'disabled' : '' }}
                        value="{{ old('rate_exchange', isset($invoice) ? $invoice->rate_exchange : 1) }}">
                    <x-message-error :hasError="$errors->has('rate_exchange')" :message="$errors->first('rate_exchange')"> </x-message-error>
                </div>
            </div>
        </div>

        {{-- Monto --}}
        <div class="col-12 col-sm-4 col-md-3">
            {{-- Monto --}}
            <div class="row p-0 align-items-center">
                <div class="col-3 col-xl-3 col-lg-4 text-right px-1  ">
                    <span class="form-label">Monto</sp>
                </div>
                <div class="col">
                    <input type="number" name="mount" id="mount" class="form-control" step="any"
                        value="{{ old('mount', isset($invoice) ? $invoice->mount : 0) }}"
                        {{ isset($invoice) ? 'disabled' : '' }} onkeyup="CalculateMountOtherCoin()" />
                </div>
                <x-message-error :hasError="$errors->has('mount')" :message="$errors->first('mount')"> </x-message-error>
            </div>
        </div>
        {{-- Observaciones --}}
        <div class="col-12 col-md-4">
            <div class="row p-0 align-items-center">
                <div class="col-3 col-md-5 col-xl-4 text-right  px-1 align-items-center ">
                    <span class="form-label">Observaciones</span>
                </div>
                <div class="col">
                    <input type="text" name="observations" id="observations" class="form-control"
                        value="{{ old('observations', isset($invoice) ? $invoice->observations : '') }}"
                        {{ isset($invoice) ? 'disabled' : '' }} />
                </div>
            </div>
        </div>
    </div>
</div>
