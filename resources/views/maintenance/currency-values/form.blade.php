<input type="hidden" value="{{ auth()->user()->id }}" name="user_id">
<div class="row">
    <div class="col-sm-3 float-end">
        <label class="mt-3 inline">{{ __('Moneda Base de Relacion: ') }} </label>
    </div>
    <div class="col-sm-8" style="margin-left: -3rem">
        <select name="base_currency_id" class="col-sm-8 form-control">
            <option value=0> Seleccione Moneda ... </option>
            @foreach ($baseCurrency as $coin)
                <option value="{{ $coin->id }}"> {{ $coin->name }} </option>
            @endforeach
        </select>
        <x-message-error :hasError="$errors->has('base_currency_id')" :message="$errors->first('base_currency_id')"></x-message-error>

    </div>
</div>
<div class="row">
    <label class="col-sm-3 float-end float-right mt-3">{{ __('Moneda a Relacionar:') }} </label>
    <div class="col-sm-8" style="margin-left: -3rem">
        <select name="coin_id" class="col-sm-8 form-control">
            <option value=0> Seleccione Moneda ... </option>
            @foreach ($coins as $coin)
                <option value="{{ $coin->id }}"> {{ $coin->name }} </option>
            @endforeach
        </select>
        <x-message-error :hasError="$errors->has('coin_id')" :message="$errors->first('coin_id')"></x-message-error>

    </div>
</div>
<div class="row">
    <label class="col-sm-3 mt-3">{{ __('Precio Compra: ') }}</label>
    <div class="col-sm-8" style="margin-left: -3rem">
        <div class="form-group{{ $errors->has('purchase_price') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('purchase_price') ? ' is-invalid' : '' }}" name="purchase_price" id="input-purchase_price"
                type="number" step="any" required="true" aria-required="true" value="{{ old('purchase_price', 0) }}" />
            <x-message-error :hasError="$errors->has('purchase_price')" :message="$errors->first('purchase_price')"></x-message-error>
        </div>
    </div>
</div>
<div class="row">
    <label class="col-sm-3 mt-3">{{ __('Precio Venta: ') }}</label>
    <div class="col-sm-8" style="margin-left: -3rem">
        <div class="form-group{{ $errors->has('sale_price') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('sale_price') ? ' is-invalid' : '' }}" name="sale_price" id="input-sale_price" type="number"
                step="any" required aria-required value="{{ old('sale_price', 0) }}" />
            <x-message-error :hasError="$errors->has('sale_price')" :message="$errors->first('sale_price')"></x-message-error>
        </div>
    </div>
</div>
