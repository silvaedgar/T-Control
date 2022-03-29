<div class="row">
    <input type="hidden" value ="{{ $base_currency->id }}" name = "base_currency_id">
    <input type="hidden" value ="{{ auth()->user()->id }}" name = "user_id">

    <label class="col-sm-12 text-center">{{ __('Moneda Base de Calculo') }} {{$base_currency->name}} </label>
    <label class="col-sm-3 col-form-label">{{ __('Moneda a Relacionar') }}</label>
    <div class="col-sm-7">
      <div class="form-group{{ $errors->has('base_coin') ? ' has-danger' : '' }}">
        <select name="coin_id" class="form-control">
            <option value = 0> Seleccione Moneda ... </option>
                @foreach ($coins as $coin)
                    <option
                        @if (isset($calccoin))
                            @if ($coin->id == $base_currency->id)
                                    selected
                            @endif
                        @endif
                    value = "{{ $coin->id }}"> {{ $coin->name }} </option>
                @endforeach
        </select>

{{--
        {{ Form::select('coin_id', $coins, null, ['class' => 'form-control']) }} --}}
        @if ($errors->has('coin_id'))
          <span id="coin_id-error" class="error text-danger" for="input-coin_id">{{ $errors->first('coin_id') }}</span>
        @endif
      </div>
    </div>

    <label class="col-sm-3 col-form-label">{{ __('Precio Compra') }}</label>
    <div class="col-sm-8">
      <div class="form-group{{ $errors->has('purchase_price') ? ' has-danger' : '' }}">
        <input class="form-control{{ $errors->has('purchase_price') ? ' is-invalid' : '' }}" name="purchase_price"
            id="input-purchase_price" type="number" step = "any" required="true" aria-required="true"
            value="{{ old('purchase_price',0) }}" />
        @if ($errors->has('purchase_price'))
          <span id="purchase_price-error" class="error text-danger" for="input-purchase_price">{{ $errors->first('purchase_price') }}</span>
        @endif
      </div>
    </div>
    <label class="col-sm-3 col-form-label">{{ __('Precio Venta') }}</label>
    <div class="col-sm-8">
      <div class="form-group{{ $errors->has('sale_price') ? ' has-danger' : '' }}">
        <input class="form-control{{ $errors->has('sale_price') ? ' is-invalid' : '' }}" name="sale_price"
            id="input-sale_price" type="number" step = "any" required aria-required
            value="{{ old('sale_price',0) }}" />
        @if ($errors->has('sale_price'))
          <span id="sale_price-error" class="error text-danger" for="input-sale_price">{{ $errors->first('sale_price') }}</span>
        @endif
      </div>
    </div>
</div>
