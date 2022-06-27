@extends('layouts.app', ['activePage' => 'sales', 'titlePage' => __('Modulo de Clientes')])


@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection


@section('content')
    <div class="content" style="margin-top:40px">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        @include('shared.header')
                    </div>
                    <div class="card-body">
                        <div class="container mx-auto" style="width:15rem">
                            <a href="{{ route('clients.balance', [$movements[0]->client_id, $mensaje]) }}"
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
