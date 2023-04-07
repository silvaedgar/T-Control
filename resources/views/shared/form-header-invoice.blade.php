<input id="mount" name="mount" type="hidden">

<div class="row">
    {{-- <div class="col-sm-12"> --}}
    <div class="row mt-lg-2 d-flex flex-wrap">
        {{--  <div class="col-2 col-sm-1 mt-sm-3  mt-md-3 ">  --}}
        <div class="col-2 col-sm-1 mt-md-2 ">
            <label for="" class="form-label"> {{ $config['var_header']['labelCaption'] }}:
            </label>
        </div>
        {{--  <div class="col-9 ms-3 col-sm-6 mt-sm-3 col-md-2 ms-sm-4 col-md-3 col-lg-4 ms-md-2 ms-lg-0  mt-md-3">  --}}
        <div class="col-8 col-md-3 col-xl-2 mt-md-2 ms-xl-0 ms-md-2 ms-sm-0 ms-3">

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
            <x-message-error :hasError="$errors->has($config['var_header']['name'])" :message="$errors->first($config['var_header']['name'])"></x-message-error>

        </div>
        {{--  Fecha  --}}
        <div class="col-2 col-sm-1 mt-2">
            <label for="" class="form-label">Fecha: </label>
        </div>
        <div class="col-10 col-md-3 col-xl-2 py-0 ">
            @php
                $date = $config['var_header']['date'];
                // usado ya que np toma $invoice->$config['var_header']['date']
            @endphp
            <input type="date" name="{{ $config['var_header']['date'] }}" class="py-0 form-control"
                {{ isset($invoice) ? 'disabled' : '' }}
                value="{{ old($config['var_header']['date'], isset($invoice) ? $invoice->$date : date('Y-m-d')) }}">
            <x-message-error :hasError="$errors->has($config['var_header']['date'])" :message="$errors->first($config['var_header']['date'])"></x-message-error>
        </div>
        {{--  Factura  --}}
        <div class="col-2 col-sm-1 mt-2 ">
            <label for="" class="form-label">Factura</label>
        </div>
        <div class="col-10 col-sm-2 col-xl-1 ">
            <input type="text" name="invoice" id="invoice" class="form-control"
                {{ isset($invoice) ? 'disabled' : '' }}
                value="{{ old('invoice', isset($invoice) ? $invoice->invoice : '') }}">
        </div>
        {{--  Condiciones  --}}
        <div class="col-2 col-sm-1 mt-3">
            <label for="" class="form-label">Condiciones</label>

        </div>
        <div class="col-8 col-sm-2 col-xl-3 col-md-8 mt-md-3 ms-xl-0 ms-md-4 mt-sm-0 mt-3 ms-5"
            {{ isset($invoice) ? 'disabled' : '' }}>
            <input type="radio" name="conditions" value='Credito' id="conditions" checked> Credito
            <input type="radio" name="conditions" value='Contado' id="conditions"> Contado
        </div>

    </div>
    <div class="row">
        {{--  Moneda  --}}
        <div class="col-2 col-sm-1 mt-3">
            <label for="" class="form-label">Moneda</label>
        </div>
        <div class="col-5 col-sm-1 ">
            <select name="coin_id" id="coin_id" class="form-control"
                onchange="ChangeCoin('{{ $config['var_header']['labelCaption'] }}')"
                {{ isset($invoice) ? 'disabled' : '' }}>

                @foreach ($config['data']['coins'] as $coin)
                    <option value="{{ $coin->id }}"
                        {{ old('coin_id') == $coin->id || (isset($invoice) && $invoice->coin_id == $coin->id) ? 'selected' : '' }}>
                        {{ $coin->symbol }}
                    </option>
                @endforeach
            </select>
            <x-message-error :hasError="$errors->has('coin_id')" :message="$errors->first('coin_id')"></x-message-error>
        </div>
        {{--  Tasa  --}}
        <div class="col-4 col-sm-2 mt-2">
            <input type="text" name="rate_exchange" id="rate_exchange" class="form-control" step="any"
                {{ isset($invoice) ? 'disabled' : '' }} onfocus="JumpRateExchange()"
                onkeyup="RecalculateInvoice(false,event)"
                value="{{ old('rate_exchange', isset($invoice) ? $invoice->rate_exchange : 1) }}">
            @if ($errors->has('rate_exchange'))
                <span id="rate_exchange-error" class="error text-danger"
                    for="input-rate_exchange">{{ $errors->first('rate_exchange') }}</span>
            @endif
            <x-message-error :hasError="$errors->has('rate_exchange')" :message="$errors->first('rate_exchange')"></x-message-error>
        </div>

        {{--  Notas  --}}
        <div class="col-2 col-sm-1 mt-3">
            <label for="" class="form-label">Notas</label>
        </div>
        <div class="col-9 col-sm-4 mt-1">
            <input type="text" name="observations" id="observations" class="form-control"
                {{ isset($invoice) ? 'disabled' : '' }}
                value="{{ old('observations', isset($invoice) ? $invoice->observations : '') }}">
        </div>
        <div class="col-2 col-sm-1 mt-3">
            <label for="" class="form-label">Costos</label>
        </div>
        <div class="col-8 col-sm-2 ">
            <input type="text" name="associated_costs" id="associated_costs" class="form-control"
                {{ isset($invoice) ? 'disabled' : '' }} onkeyup="CalcInvoice()"
                value="{{ old('associated_costs', isset($invoice) ? $invoice->associated_costs : '') }}">
        </div>
    </div>
</div>
