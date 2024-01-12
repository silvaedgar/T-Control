<input id="mount" name="mount" type="hidden">

<div class="container">
    <div class="row mt-1 mt-lg-0">
        {{-- Proveedor Cliente --}}
        <div class="col-12 col-md-6 col-lg-3 mt-lg-2">
            <div class="row p-0  align-items-center ">
                <div class="col-3 col-md-4 text-right px-1 ">
                    <label class="form-label"> {{ $config['var_header']['labelCaption'] }}</label>
                </div>
                <div class="col px-1 ml-0">
                    @if (isset($invoice))
                        <input type="text" value="{{ $config['var_header']['name'] }}" class="form-control " />
                    @else
                        @php
                            $variable = $config['var_header']['name'];
                        @endphp
                        <select name="{{ $config['var_header']['name'] }}" id="{{ $config['var_header']['name'] }}"
                            class="form-select select2" {{ isset($invoice) ? 'disabled' : '' }}>
                            <option value=0> Seleccione un {{ $config['var_header']['labelCaption'] }} ... </option>
                            @foreach ($config['var_header']['table'] as $table)
                                <option value="{{ $table->id }}"
                                    {{ old($config['var_header']['name']) == $table->id || (isset($invoice) && $invoice->$variable == $table->id) ? 'selected' : '' }}>
                                    {{ isset($table->names) ? $table->names : $table->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <x-message-error :hasError="$errors->has($config['var_header']['name'])" :message="$errors->first($config['var_header']['name'])"></x-message-error>
            </div>
        </div>
        {{-- Fecha --}}
        <div class="col-12 mt-2 col-sm-4 col-md-6 mt-md-0 col-lg-3  ">
            <div class="row p-0 align-items-center">
                <div class="col-3 px-1 text-right mt-lg-2  ">
                    <label class="form-label">Fecha </label>
                </div>
                <div class="col col-sm-9">
                    @php
                        $date = $config['var_header']['date'];
                        // usado ya que np toma $invoice->$config['var_header']['date']
                    @endphp
                    <input type="date" name="{{ $config['var_header']['date'] }}" class="py-0 form-control"
                        {{ isset($invoice) ? 'disabled' : '' }}
                        value="{{ old($config['var_header']['date'], isset($invoice) ? $invoice->$date : date('Y-m-d')) }}">
                    <x-message-error :hasError="$errors->has($config['var_header']['date'])" :message="$errors->first($config['var_header']['date'])"></x-message-error>
                </div>
            </div>

        </div>
        {{-- Factura --}}
        <div class="col-12 mt-2 mt-lg-0 col-sm-8 col-md-6 col-lg-2">
            <div class="row p-0 align-items-center">
                <div class="col-3 px-1 text-right mt-lg-2">
                    <label class="form-label">Factura</label>
                </div>
                <div class="col ">
                    <input type="text" name="invoice" id="invoice" class="form-control"
                        {{ isset($invoice) ? 'disabled' : '' }}
                        value="{{ old('invoice', isset($invoice) ? $invoice->invoice : '') }}">
                </div>

            </div>
        </div>
        {{-- Condiciones --}}
        <div class="col-12 col-md-6 col-lg-4 mt-3 mt-lg-2">
            <div class="row p-0 align-items-center ">
                <div class="col-3 col-lg-4 px-1 text-right">
                    <label class="form-label">Condiciones</label>

                </div>
                <div class="col col-lg-8" {{ isset($invoice) ? 'disabled' : '' }}>
                    <input type="radio" name="conditions" value='Credito' id="conditions" checked> Credito
                    <input type="radio" name="conditions" value='Contado' id="conditions"> Contado
                </div>

            </div>
        </div>
    </div>

    <div class="row mt-1">
        {{-- Moneda y tasa --}}
        <div class="col-12 col-md-5 mt-lg-1">
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
                            onchange="ChangeCoin('{{ $config['var_header']['labelCaption'] }}')"
                            {{ isset($invoice) ? 'disabled' : '' }} style="height: 30px; font-size:13px">
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
                {{-- Tasa --}}
                <div class="col-3  px-1 align-items-center ">
                    <input type="number" name="rate_exchange" id="rate_exchange" class="form-control"
                        onfocus="JumpRateExchange()" onkeyup="CalculateOtherMount()" step="any"
                        {{ isset($invoice) ? 'disabled' : '' }}
                        value="{{ old('rate_exchange', isset($invoice) ? $invoice->rate_exchange : 1) }}">
                    <x-message-error :hasError="$errors->has('rate_exchange')" :message="$errors->first('rate_exchange')"> </x-message-error>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="row p-0 align-items-center">
                {{--  Notas  --}}
                <div class="col-3 text-right px-1 align-items-center mt-lg-2">
                    <span class="form-label">Notas</span>
                </div>
                <div class="col-9  mt-1">
                    <input type="text" name="observations" id="observations" class="form-control"
                        {{ isset($invoice) ? 'disabled' : '' }}
                        value="{{ old('observations', isset($invoice) ? $invoice->observations : '') }}">
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="row p-0 align-items-center">

                <div class="col-3 col-md-4 text-right px-1 align-items-center ">
                    <span class="form-label">Costos</span>
                </div>
                <div class="col-9  col-md-8 ">
                    <input type="text" name="associated_costs" id="associated_costs" class="form-control"
                        {{ isset($invoice) ? 'disabled' : '' }} onkeyup="CalcInvoice()"
                        value="{{ old('associated_costs', isset($invoice) ? $invoice->associated_costs : '') }}">
                </div>
            </div>
        </div>
    </div>
</div>
