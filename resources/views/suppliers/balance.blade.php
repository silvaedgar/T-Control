@extends('layouts.app', ['activePage' => 'purchases', 'titlePage' => __('Modulo de Proveedores')])

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content" style="margin-top:40px">
        <input type="hidden" id="id" value="{{ $movements[0]->supplier }}">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        @include('shared.header')
                        {{-- <div class="row">
                                <div class="col-sm-4 ">
                                    <span style="font-size:17px">Detalle de Movimientos </span> <br />
                                    <span id="base_calc_name" style="font-size: 13px"> Moneda de Calculo:
                                        {{ $base_coin->symbol }}</span>
                                </div>
                                <div class="col-sm-5 col-lg-4">
                                    <span id="mountlabel" style="font-size:17px"> Proveedor: {{ $movements[0]->name }}
                                    </span><br />
                                    <span style="font-size: 14px"> Saldo: {{ $movements[0]->balance }}
                                        {{ $base_coin->symbol }}</span>
                                </div>
                                <div class="col-sm-3 col-md-3 col-lg-2 col-xl-1" style="font-size:17px">
                                    <a class="text-white" href="{{ url()->previous() }}">
                                        {{ __('Atras') }} </a> <br />
                                    <a style="color: #99ffff; font-size: 12px" href="{{ route('purchases.create') }}">
                                        Crear Factura </a> /
                                    <a style="color: #99ffff; font-size: 12px"
                                        href="{{ route('paymentsuppliers.create') }}">
                                        Generar Pago </a>

                                </div>
                            </div> --}}
                    </div>
                    <div class="card-body">
                        <div class="container mx-auto" style="width:15rem">
                            <a href="{{ route('suppliers.balance', [$movements[0]->supplier_id, $mensaje]) }}"
                                class="text-danger">
                                {{ $mensaje }} </a>
                        </div>
                        @include('shared.table-balance')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js') }}/globalvars.js"></script>
@endpush
