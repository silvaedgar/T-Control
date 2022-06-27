@extends('layouts.app', ['activePage' => 'sales', 'titlePage' => __('Modulo de Pago de Clientes')])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection
@section('content')
    <div class="content" style="margin-top: 40px">
        {{-- Input que se usan en JavaScript para operaciones no se necesitan en el controlador --}}
        <input type="hidden" id="calc_currency_id" value="{{ $data_common['calc_coin_id'] }}">
        <input type="hidden" id="base_currency_id" value="{{ $data_common['base_coin_id'] }}">
        <input type="hidden" id="calc_currency_symbol" value="{{ $data_common['calc_coin_symbol'] }}">
        <input type="hidden" id="base_currency_symbol" value="{{ $data_common['base_coin_symbol'] }}">
        <input type="hidden" id="factor">
        <input type="hidden" id="last_rate">
        <input type="hidden" id="count_in_bs" value="N">

        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ route('paymentclients.store') }}" autocomplete="off"
                    class="form-horizontal">
                    @csrf
                    <div class="card">
                        <input type="hidden" id="rate" name="rate_date" value="{{ $data_common['rate'] }}">
                        <input type="hidden" id="calc_currency_id" name="calc_currency_id"
                            value="{{ $data_common['calc_coin_id'] }}">

                        <div class="card-header card-header-primary">
                            @include('shared.header')
                        </div>
                        <div class="card-body ">
                            @include('shared.form-header-payment')
                            @include('payment-clients.formdetails')
                            <div class="row mt-1">
                                <div class="col-sm-5 mt-3"> <a href="{{ route('paymentclients.index') }}">
                                        {{ __('Ir a Listado de Pagos') }} </a>
                                </div>
                                <div class="col-sm-7">
                                    <button type="submit" class="btn btn-primary">{{ __('Procesar Pago') }}</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
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
