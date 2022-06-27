    {{-- <input type="hidden" value ="{{ $base_currency->id }}" name = "base_currency_id"> --}}
    <input type="hidden" value ="{{ auth()->user()->id }}" name = "user_id">
    {{-- <label class="col-sm-12 text-center">{{ __('Moneda Base de Calculo') }} {{$base_currency->name}} </label>
    <label class="col-sm-3 col-form-label">{{ __('Moneda a Relacionar') }}</label> --}}
    <div class="row">
        <label class="col-sm-2 text-center">{{ __('Moneda Base de Relacion') }}  </label>
        <select name="base_currency_id" class="col-sm-8 form-control">
            <option value = 0> Seleccione Moneda ... </option>
                 @foreach ($base_currency as $coin)
                     <option value = "{{ $coin->id }}"> {{ $coin->name }} </option>
                 @endforeach
        </select>
        @if ($errors->has('base_currency_id'))
             <span id="base_currency_id-error" class="error text-danger" for="input-base_currency_id">{{ $errors->first('base_currency_id') }}</span>
        @endif
    </div>
    <div class="row">
        <label class="col-sm-2">{{ __('Moneda a Relacionar') }}  </label>
        <select name="coin_id" class="col-sm-8 form-control">
            <option value = 0> Seleccione Moneda ... </option>
                @foreach ($coins as $coin)
                    <option value = "{{ $coin->id }}"> {{ $coin->name }} </option>
                @endforeach
        </select>
        @if ($errors->has('coin_id'))
          <span id="coin_id-error" class="error text-danger" for="input-coin_id">{{ $errors->first('coin_id') }}</span>
        @endif
    </div>
    <div class="row">
        <label class="col-sm-2 col-form-label">{{ __('Precio Compra') }}</label>
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
    </div>
    <div class="row">
        <label class="col-sm-2 col-form-label">{{ __('Precio Venta') }}</label>
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
