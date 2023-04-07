@extends('layouts.app', ['activePage' => $config['layout']['activePage'], 'titlePage' => __($config['layout']['titlePage'])])

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection


@section('content')
    <div class="content" style="margin-top: 40px">
        <div class="card">
            <div class="card-header card-header-primary">
                <x-header-index-filter :config="$config"></x-header-index-filter>
            </div>
            <form class="mt-3" method="get" action="{{ route($config['router']['routeFilter']) }}">
                @csrf
                @include('shared.filter')
            </form>
            <div class="card-body">

                <div class="table-responsive">
                    {{-- :collection="$config['data']['collection']"                     --}}
                    <x-table-component :data="$config['data']" :header="$config['table']['header']" :include="$config['table']['include']">
                    </x-table-component>
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
