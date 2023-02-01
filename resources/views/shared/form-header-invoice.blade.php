<div class="row">
    @php
        $calc_coin_id = $data_common['calc_coin_id'];
        $controller = $data_common['controller'];
        $option = $controller == 'Sale' || $controller == 'Purchase' ? 'Invoice' : '';
    @endphp

    <input type="hidden" value="{{ auth()->user()->id }}" name="user_id">
    <div class="col-sm-12 ">
        <div class="row">
            <div class="col-sm-2 col-md-1 mt-md-3">
                <label for="" class="form-label"> {{ $controller == 'Sale' ? 'Cliente' : 'Proveedor' }}
                </label>
            </div>
            <div class="col-sm-10 col-md-3 col-lg-4 ml-md-2 mt-md-3 ">
                @if ($controller == 'Sale')
                    <select name="client_id" id="client_id" class="form-control"
                        {{ isset($data['invoice']) ? 'disabled' : '' }}
                        value={{ old('client_id', isset($data['invoice']) ? $data['invoice']->client_id : '') }}>
                        <option value=0> Seleccione un Cliente ... </option>
                        @foreach ($data['clients'] as $client)
                            @php
                                $selected = isset($data['invoice']) ? true : false;
                                $selected = (old('client_id') > 0 && old('client_id') == $client->id) || $selected ? true : false;

                                // $selected = isset($invoice) && $invoice->client_id == $client->id ? true : false;
                                // $selected = (old('client_id') > 0 && old('client_id') == $client->id) || $selected ? true : false;

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
                @else
                    <select name="supplier_id" id="supplier_id" class="form-control"
                        {{ isset($data['invoice']) ? 'disabled' : '' }}
                        value={{ old('supplier_id', isset($data['invoice']) ? $data['invoice']->Supplier->id : '') }}>
                        <option value=0> Seleccione un Proveedor ... </option>
                        @foreach ($data['suppliers'] as $supplier)
                            @php
                                $selected = isset($data['invoice']) ? true : false;
                                $selected = (old('supplier_id') > 0 && old('supplier_id') == $supplier->id) || $selected ? true : false;
                            @endphp
                            {{-- @php
                                $selected = isset($invoice) && $invoice->supplier_id == $supplier->id ? true : false;
                                $selected = (old('supplier_id') > 0 && old('supplier_id') == $supplier->id) || $selected ? true : false;
                            @endphp --}}
                            <option value="{{ $supplier->id }}" {{ $selected ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('supplier_id'))
                        <span id="supplier_id-error" class="error text-danger"
                            for="input-supplier_id">{{ $errors->first('supplier_id') }}</span>
                    @endif
                @endif
            </div>
            <div class="col-sm-1 mt-sm-3">
                <label for="" class="form-label">Fecha: </label>
            </div>
            <div class="col-sm-4 col-md-3 col-lg-2  ml-sm-2 mt-sm-2">
                @if ($controller == 'Sale')
                    <input type="date" name="sale_date" id="sale_date" class="form-control"
                        {{ isset($data['invoice']) ? 'disabled' : '' }}
                        value="{{ old('sale_date', isset($data['invoice']) ? $data['invoice']->sale_date : date('Y-m-d')) }}">
                    @if ($errors->has('sale_date'))
                        <span id="sale_date-error" class="error text-danger"
                            for="input-sale_date">{{ $errors->first('sale_date') }}</span>
                    @endif
                @else
                    <input type="date" name="purchase_date" id="purchase_date" class="form-control"
                        {{ isset($data['invoice']) ? 'disabled' : '' }}
                        value="{{ old('purchase_date', isset($data['invoice']) ? $data['invoice']->purchase_date : date('Y-m-d')) }}">
                    @if ($errors->has('purchase_date'))
                        <span id="purchase_date-error" class="error text-danger"
                            for="input-purchase_date">{{ $errors->first('purchase_date') }}</span>
                    @endif

                @endif
            </div>
            <div class="col-sm-1 mt-sm-3 ml-lg-4">
                <label for="" class="form-label">Factura</label>
            </div>
            <div class="col-sm-4 col-md-2  ml-sm-3 mt-sm-2 mr-lg-3">
                <input type="text" name="invoice" id="invoice" class="form-control"
                    {{ isset($data['invoice']) ? 'disabled' : '' }}
                    value="{{ old('invoice', isset($data['invoice']) ? $data['invoice']->invoice : '') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1 mt-sm-2">
                <label for="" class="form-label">Moneda</label>
            </div>
            <div class="col-sm-2 col-md-1 col-xl-1 ml-sm-3">
                <select name="coin_id" id="coin_id" class="form-control"
                    onchange="SearchCoinBase('{{ $controller }}','{{ $option }}')"
                    {{ isset($invoice) ? 'disabled' : '' }}>
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
            <div class="col-sm-2 mt-sm-2">
                <input type="number" name="rate_exchange" id="rate_exchange" class="form-control" step="any"
                    {{ isset($data['invoice']) ? 'disabled' : '' }} onfocus="VerifyGetFocusRateExchange()"
                    onkeyup="RecalculateInvoice(false); CalcSubtotal('Price')"
                    value="{{ isset($data['invoice']) ? $data['invoice']->rate_exchange : 1 }}">
                @if ($errors->has('rate_exchange'))
                    <span id="rate_exchange-error" class="error text-danger"
                        for="input-rate_exchange">{{ $errors->first('rate_exchange') }}</span>
                @endif
            </div>
            <div class="col-sm-2 col-md-1 col-xl-1 mt-sm-3">
                <label for="" class="form-label">Condiciones</label>
            </div>
            <div class="col-sm-4  col-md-3 col-xl-2 mt-sm-3 ml-md-4" {{ isset($data['invoice']) ? 'disabled' : '' }}>
                <input type="radio" name="conditions" value='Credito' id="conditions" checked> Credito
                <input type="radio" name="conditions" value='Contado' id="conditions"> Contado
            </div>
            <div class="col-sm-1 mt-sm-3">
                <label for="" class="form-label">Notas</label>
            </div>
            <div class="col-sm-10 col-md-2 col-xl-3">
                <input type="text" name="observations" id="observations" class="form-control"
                    {{ isset($data['invoice']) ? 'disabled' : '' }}
                    value="{{ old('observations', isset($data['invoice']) ? $data['invoice']->observations : '') }}">
            </div>
        </div>

    </div>
</div>
