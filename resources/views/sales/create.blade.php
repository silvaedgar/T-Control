@extends('layouts.app', ['activePage' => 'sales', 'titlePage' => __('Modulo de Ventas')])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection

@section('content')
<div class="content">
    <input type="hidden" id = "base_currency" value = "{{ $base_coins['base_id']}}">
    <input type="hidden" id = "calc_currency" value = "{{ $base_coins['base_calc_id']}}">
    <input type="hidden" id = "factor">
    <input type="hidden" id = "last_rate">
    <input id="symbol_coin" type="hidden" value = "{{ $base_coins['base_calc_symbol']}}">
    <input id="symbol_coin_calc" type="hidden" value = "{{ $base_coins['base_calc_symbol']}}">

      <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{route('sales.store')}}" autocomplete="off" class="form-horizontal">
            @csrf
            <div class="card ">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-sm-5 col-xl-4">
                            <h4 class="card-title">{{ __('Creando Factura de Venta') }} </h4>
                        </div>
                        <div class="col-sm-3 justify-center">
                            <h5> Monto Factura: <span id="mountlabel">  </h5>
                            @if ($errors->has('mount'))
                                <span id="mount-error" class="error text-light" for="input-mount">{{ $errors->first('mount') }}</span>
                            @endif

                        </div>
                        <div class="col-sm-2">
                            <h5 class="card-category" id="payment_mount"></h5>
                        </div>
                        <div class="col-sm-2 justify-end">
                            <a class="text-white " href = "{{ route('purchases.index') }}"> {{ __('Volver al listado') }} </a>
                        </div>
                    </div>
                    <span id="base_calc_name" > Moneda de Calculo: {{ $base_coins['base_calc_name']}} </span>
                    <input id="mount" name = "mount" type="hidden">
                    <input id="tax" name = "tax_mount" type="hidden">

                </div>
                <div class="card-body ">
                @if (session('status'))
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="material-icons">close</i>
                        </button>
                        <span>{{ session('status') }}</span>
                      </div>
                    </div>
                  </div>
                @endif
                @include('sales.formheader')
                @include('sales.formdetails')
              </div>
            </div>
          </form>
        </div>
      </div>
</div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js') }}/globalvars.js"> </script>
    <script src="{{ asset('js') }}/searchfunctions.js"> </script>
    <script src="{{ asset('js') }}/tabledetails.js"> </script>
@endpush

