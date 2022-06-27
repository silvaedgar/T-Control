@extends('layouts.app', ['activePage' => 'sales', 'titlePage' => __('Modulo de Ventas')])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content" style="margin-top: 40px">
        <input type="hidden" id="calc_currency_id" value="{{ $data_common['calc_coin_id'] }}">
        <input type="hidden" id="base_currency_id" value="{{ $data_common['base_coin_id'] }}">
        <input type="hidden" id="calc_currency_symbol" value="{{ $data_common['calc_coin_symbol'] }}">
        <input type="hidden" id="base_currency_symbol" value="{{ $data_common['base_coin_symbol'] }}">
        <input type="hidden" id="factor">
        <input type="hidden" id="last_rate">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ route('sales.store') }}" autocomplete="off" class="form-horizontal">
                    @csrf
                    <div class="card ">
                        <div class="card-header card-header-primary">
                            @include('shared.header')
                            <input id="mount" name="mount" type="hidden">
                            <input id="tax" name="tax_mount" type="hidden">
                            <input type="hidden" name="rate_exchange_date" id="rate"
                                value="{{ $data_common['rate'] }}">
                        </div>
                        <div class="card-body ">
                            @include('shared.form-header-invoice')
                            @include('shared.form-details-invoice')
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
