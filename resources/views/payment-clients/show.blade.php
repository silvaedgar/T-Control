@extends('layouts.app', ['activePage' => 'paymentclients', 'titlePage' => __('Modulo de Pago de Clientes')])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    {{-- <input type="hidden" id="calc_currency" value="{{ $paymentclient->coin_id }}"> --}}
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header card-header-primary">
                        @include('shared.header')

                        {{-- <div class="row">
                    <div class="col-sm-3 col-xl-2">
                        <h4 class="card-title">{{ __('Detalle de Pago') }} </h4>
                    </div>
                    <div class="col-sm-5 col-xl-4">
                        <h5> Monto: {{ number_format($paymentclient->mount,2) }}
                                {{ $paymentclient->simbolo }}</h5>
                    </div>
                    <div class="col-sm-3">
                        @if ($base_coins['base_calc_id'] != $paymentclient->coin_id)
                            <h5>  Monto en
                                {{ ($paymentclient->coin_id != 1 ? "BsD" : $base_coins->symbol) }}
                                {{ ($paymentclient->simbolo == 'BsD' ? number_format($paymentclient->mount / $paymentclient->rate_exchange,2) : number_format($paymentclient->mount * $paymentclient->rate_exchange,2) )}}
                            </h5>
                        @endif
                    </div>
                    <div class="col-sm-4 col-xl-3 float-rigth">
                        <a class="text-white " href = "{{ url()->previous() }}"> {{ __('Volver Atras') }} </a>

                    </div>
                </div>
                <span id="base_calc_name"> Moneda de Calculo: {{ $base_coins->name }} ---  Tasa de Pago: {{ $paymentclient->rate_exchange }} </span> --}}
                    </div>
                    <div class="card-body ">
                        @include('shared.form-header-payment')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js') }}/globalvars.js"></script>
    <script src="{{ asset('js') }}/searchfunctions.js"></script>
    <script src="{{ asset('js') }}/tabledetails.js"></script>
@endpush
