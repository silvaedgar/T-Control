@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __(('Modulo de Monedas'))])

@section('content')
  <div class="content">
    <div class="container-fluid">
        <div class="card mx-auto">
            <div class="card-header card-header-primary">
              <h4 class="card-title">{{ __('Moneda Base') }}</h4>
              <p class="card-category">{{ __('Establecer moneda base y de calculo') }}</p>
            </div>
            <div class="card-body">
                <form method="post" action="{{route('coinbase.update')}}" autocomplete="off" class="form-horizontal">
                    @csrf
                    @method('put')
                <div class="row">
                    <label class="col-sm-3 col-form-label">{{ __('Moneda Base') }}</label>
                    <div class="col-sm-7">
                      <div class="form-group{{ $errors->has('base_currency') ? ' has-danger' : '' }}">
                        <select name="base_currency" class="form-control">
                            <option value = 0> Seleccione Moneda ... </option>
                            @foreach ($coins as $coin)
                                <option
                                @if (isset($basecoin))
                                    @if ($coin->id == $basecoin->id)
                                        selected
                                    @endif
                                @endif
                                value = "{{$coin->id}}"> {{ $coin->name }} </option>
                            @endforeach
                        </select>
                        {{-- {{ Form::select('base_coin', $basecoin, (isset($basecoin) ? $basecoin->id: null), ['class' => 'form-control']) }} --}}
                        @if ($errors->has('base_currency'))
                          <span id="base_currency-error" class="error text-danger" for="input-base_currency">{{ $errors->first('base_currency') }}</span>
                        @endif
                      </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-3 col-form-label">{{ __('Moneda Base de Calculo en Precios de Compra') }}</label>
                    <div class="col-sm-8">
                      <div class="form-group{{ $errors->has('calc_currency_purchase') ? ' has-danger' : '' }}">
                        <select name="calc_currency_purchase" class="form-control">
                            <option value = 0> Seleccione Moneda ... </option>
                                @foreach ($coins as $coin)
                                    <option
                                        @if (isset($calccoinpurchase))
                                            @if ($coin->id == $calccoinpurchase->id)
                                                    selected
                                            @endif
                                        @endif
                                    value = "{{ $coin->id }}"> {{ $coin->name }} </option>
                                @endforeach
                        </select>

                        {{-- {{ Form::select('base_calc_coin',$calccoins,isset($calccoin)? $calccoin->id :null, ['class' => 'form-control']) }} --}}
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
                                    <option
                                        @if (isset($calccoinsale))
                                            @if ($coin->id == $calccoinsale->id)
                                                    selected
                                            @endif
                                        @endif
                                    value = "{{ $coin->id }}"> {{ $coin->name }} </option>
                                @endforeach
                        </select>

                        {{-- {{ Form::select('base_calc_coin',$calccoins,isset($calccoin)? $calccoin->id :null, ['class' => 'form-control']) }} --}}
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


