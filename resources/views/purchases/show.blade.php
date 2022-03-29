@extends('layouts.app', ['activePage' => 'purchases', 'titlePage' => __('Modulo de Compras')])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

{{-- <input type="hidden" id = "base_currency" value = "{{ $base_coins['base_id']}}"> --}}
<input type="hidden" id = "calc_currency" value = "{{ $purchase->coin_id}}">
<div class="content">

<div class="row">
        <div class="col-md-12">
            <div class="card ">
                {{-- <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-sm-5 col-xl-4">
                            <h4 class="card-title">{{ __('Creando Factura de Compra') }} </h4>
                        </div>
                        <div class="col-sm-3 justify-center">
                            <h5> Monto Factura <span id="mountlabel">  </h5>
                        </div>
                        <div class="col-sm-2">
                            <h5 class="card-category" id="payment_mount"></h5>
                        </div>

                    </div>
                    <span id="base_calc_name"> Moneda de Calculo: {{ $base_coins['base_calc_name']}} </span>
                    <input id="mount" name = "mount" type="hidden">
                    <input id="tax" name = "tax_mount" type="hidden">
                </div> --}}

              <div class="card-header card-header-primary">
                  <div class="row">
                    <div class="col-sm-3 col-xl-4">
                        <h4 class="card-title">{{ __('Detalle ODC') }} </h4>
                    </div>
                    <div class="col-sm-5 col-xl-5">
                        <h5> Monto Factura: <span id="mountlabel"> {{ $purchase->mount }} {{ $purchase->simbolo }} </h5>
                    </div>
                        <div class="col-sm-4 col-xl-3 justify-end">
                            <a class="text-white " href = "{{ route('purchases.index') }}"> {{ __('Volver al listado') }} </a>
                        </div>
                  </div>
                <span id="base_calc_name"> Moneda de Calculo: {{ $purchase->moneda }}</span>
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
    <script src="{{ asset('js') }}/searchfunctions1.js"> </script>
    <script src="{{ asset('js') }}/tabledetails1.js"> </script>
@endpush

