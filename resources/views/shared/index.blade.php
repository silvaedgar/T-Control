@extends('layouts.app', ['activePage' => $config['layout']['activePage'], 'titlePage' => __($config['layout']['titlePage'])])

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content overflow-scroll" style="margin-top: 40px">
        <div class="card">
            <div class="card-header card-header-primary">
                {{-- <x-header-index-filter :config="$config"></x-header-index-filter> --}}
                <x-header-index :config="$config"></x-header-index>
                <x-message-session> {{ session('message_status') }} </x-message-session>
            </div>
            @if ($config['hasFilter'])
                <form class="mt-3" method="get" action="{{ route($config['router']['routeFilter']) }}">
                    @csrf
                    @include('shared.filter')
                </form>
            @endif

            <div class="card-body">
                @php
                    // usada para el currency values. Para quitarla hay que ir a cada data de controller y anexar el item reverse
                    $reverse = isset($config['reverse']);
                @endphp
                <x-table-component :config="$config">
                </x-table-component>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js') }}/globalvars.js"></script>
@endpush
