@extends('layouts.app', ['activePage' => 'purchases', 'titlePage' => __('Modulo de Compras')])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<input type="hidden" id = "calc_currency" value = "{{ $purchase->coin_id}}">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
              <div class="card-header card-header-primary">
                  <div class="row">
                    <div class="col-sm-5 col-xl-4">
                        <h4 class="card-title">{{ __('Detalle ODC') }} </h4>
                    </div>
                    <div class="col-sm-3 justify-center">
                        <h5> Monto Factura: <span id="mountlabel"> {{ number_format($purchase->mount,2) }} {{ $purchase->simbolo }} </span> </h5>
                    </div>
                    <div class="col-sm-2">
                        @if ($base_coins['base_calc_id'] != $purchase->coin_id)
                            <h5>  Monto en {{ $base_coins['base_symbol']}}
                                {{ number_format($purchase->mount / $purchase->rate_exchange,2) }}</h5>
                        @endif
                    </div>
                    <div class="col-sm-2">
                        <a class="text-white " href = "{{ url()->previous() }}"> {{ __('Volver al listado') }} </a>
                    </div>
                  </div>
                  <span id="base_calc_name"> Moneda de Calculo: {{ $base_coins['base_calc_name']}} </span>
                </div>
              <div class="card-body ">
                @include('purchases.formheader')
                 @include('purchases.formdetails')
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
              </div>
            </div>
        </div>
      </div>
    </div>

@endsection


@push('js')
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js') }}/globalvars.js"> </script>
    <script src="{{ asset('js') }}/searchfunctions.js"> </script>
    <script src="{{ asset('js') }}/tabledetails.js"> </script>
@endpush

