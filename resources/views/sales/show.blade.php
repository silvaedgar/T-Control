@extends('layouts.app', ['activePage' => 'sales', 'titlePage' => __('Modulo de Ventas')])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<input type="hidden" id = "calc_currency" value = "{{ $sale->coin_id}}">
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
              <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-sm-3 col-xl-3">
                        <h4 class="card-title">{{ __('Detalle Factura') }} </h4>
                    </div>
                    <div class="col-sm-5 col-xl-4">
                        <h5> Monto Factura: <span id="mountlabel"> {{ number_format($sale->mount,2) }} {{ $sale->simbolo }}
                                <a href="{{ route ('sales.print',$sale->id)}}" target="_blank" class="text-white"> <button type="button"  class="bg-info" data-toggle="tooltip" data-placement="top" title="Imprimir Factura">
                                    <i class="fa fa-print" aria-hidden="true"></i>
                                </button> </a></span></h5>
                    </div>
                    <div class="col-sm-2">
                        @if ($base_coins['base_calc_id'] != $sale->coin_id)
                            <h5>  Monto en {{ $base_coins['base_symbol']}}
                                {{ number_format($sale->mount * $sale->rate_exchange,2) }}</h5>
                        @endif
                    </div>

                    <div class="col-sm-4 col-xl-3 float-rigth">
                            @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('User'))
                                <a class="text-white " href = "{{ route('sales.index') }}"> {{ __('Volver al listado') }} </a>
                            @else
                                <a class="text-white " href = "{{ url()->previous() }}"> {{ __('Volver al listado') }} </a>
                            @endif
                    </div>
                </div>
                <span id="base_calc_name"> Moneda de Calculo: {{ $sale->moneda }} </span>
              </div>
              <div class="card-body ">
                @include('sales.formheader')
                @include('sales.formdetails')
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js') }}/globalvars.js"> </script>
    <script src="{{ asset('js') }}/searchfunctions.js"> </script>
    <script src="{{ asset('js') }}/tabledetails.js"> </script>
@endpush

