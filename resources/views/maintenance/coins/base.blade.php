@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __(('Modulo de Monedas'))])

@section('content')
  <div class="content">
    <div class="container-fluid">
        <div class="card mx-auto">
            <div class="card-header card-header-primary">
              <h4 class="card-title">{{ __('Monedas Base') }}</h4>
              <p class="card-category">{{ __('Establecer monedas de calculo') }}</p>
            </div>
            <div class="card-body">
                <form method="post" action="{{route('coinbase.update')}}" autocomplete="off" class="form-horizontal">
                    @csrf
                    @method('put')
                <div class="row">
                    <label class="col-sm-3 col-form-label">{{ __('Moneda Base de Calculo en Precios de Compra') }}</label>
                    <div class="col-sm-8">
                      <div class="form-group{{ $errors->has('calc_currency_purchase') ? ' has-danger' : '' }}">
                        <select name="calc_currency_purchase" class="form-control">
                            <option value = 0> Seleccione Moneda ... </option>
                            @foreach ($coins as $coin)
                                @php
                                    $selected = (isset($calc_purchase) && $calc_purchase->id == $coin->id ? true : false);
                                    $selected = ((old('calc_currency_purchase') > 0 && old('calc_currency_purchase') == $coin->id) || $selected ? true : false);
                                @endphp
                                <option value="{{ $coin->id}}"  {{ ($selected ? 'selected' :'') }}>
                                    {{ $coin->name}}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('calc_currency'))
                          <span id="calc_currency-error" class="error text-danger" for="input-calc_currency">{{ $errors->first('calc_currency') }}</span>
                        @endif
                      </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-3 col-form-label">{{ __('Moneda Base de Calculo en Precios de Venta') }}</label>
                    <div class="col-sm-8">
                      <div class="form-group{{ $errors->has('calc_currency_sale') ? ' has-danger' : '' }}">
                        <select name="calc_currency_sale" class="form-control">
                            <option value = 0> Seleccione Moneda ... </option>
                            @foreach ($coins as $coin)
                                @php
                                    $selected = (isset($calc_sale) && $calc_sale->id == $coin->id ? true :false);
                                    $selected = ((old('calc_currency_sale') > 0 && old('calc_currency_sale') == $coin->id) || $selected ? true : false);
                                @endphp
                                <option value="{{ $coin->id}}"  {{ ($selected ? 'selected' :'') }}>
                                    {{ $coin->name}}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('calc_currency'))
                          <span id="calc_currency-error" class="error text-danger" for="input-calc_currency">{{ $errors->first('calc_currency') }}</span>
                        @endif
                      </div>
                    </div>
                </div>

                <div class="card-footer justify-content-center">
                    <button type="submit" class="btn btn-primary">{{ __('Grabar Configuracion') }}</button>
                </div>
            </form>
            </div>
        </div>
    </div>
  </div>
@endsection


