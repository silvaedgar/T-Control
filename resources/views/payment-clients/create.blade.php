@extends('layouts.app', ['activePage' => 'sales', 'titlePage' => __('Modulo de Pago de Clientes')])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection
@section('content')
<input type="hidden" id = "base_currency" value = "{{ $base_coins['base_id']}}">
<input type="hidden" id = "calc_currency" value = "{{ $base_coins['base_calc_id']}}">

    <div class="content">
      <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{route('paymentclients.store')}}" autocomplete="off" class="form-horizontal">
            @csrf
            <div class="card ">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-xl-4 col-sm-5">
                            <h4 class="card-title">{{ __('Crear Pago a Cliente') }}</h4>
                        </div>
                        <div class="col-xl-4 col-sm-4">
                            <h5 class="card-category" id="client_balance"> </h5>
                        </div>
                        <div class="col-xl-4 col-sm-3">
                            <h5 class="card-category" id="payment_mount"> </h5>
                        </div>
                        <span id="base_calc_name" class="ml-3"> Moneda de Calculo: {{ $base_coins['base_calc_name']}} </span>
                    </div>
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
                @include('payment-clients.formheader')
                @include('payment-clients.formdetails')

              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Procesar Pago') }}</button>
              </div>
            </div>
          </form>
          <a href = "{{ route('paymentclients.index') }}"> {{ __('Volver al listado') }} </a>
        </div>
      </div>
    </div>

@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js') }}/searchfunctions1.js"> </script>
    <script src="{{ asset('js') }}/tabledetails1.js"> </script>
@endpush
