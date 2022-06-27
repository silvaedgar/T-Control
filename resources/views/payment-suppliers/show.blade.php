@extends('layouts.app', ['activePage' => 'paymentsuppliers', 'titlePage' => __('Modulo de Pago de Proveedores')])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    {{-- <input type="hidden" id="calc_currency" value="{{ $payment_supplier->coin_id }}"> --}}
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header card-header-primary">
                        @include('shared.header')
                    </div>
                    <div class="card-body ">
                        @include('shared.form-header-payment')
                    </div>
                    <a href="{{ route('paymentsuppliers.index') }}"> {{ __('Ir a Listado de Pagos') }} </a>

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
