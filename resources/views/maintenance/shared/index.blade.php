@extends('layouts.app', ['activePage' => $config['layout']['activePage'], 'titlePage' => __($config['layout']['titlePage'])])

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content" style="margin-top: 40px">
        <div class="card">
            <div class="card-header card-header-primary">
                <x-header-index :title="$config['header']['title']" :button="$config['buttons']" :config="$config"></x-header-index>
                @if (session('message_status'))
                    @include('shared.message-session')
                @endif
            </div>
            {{ $config }}
            <div class="card-body">
                @php
                    // usada para el currency values. Para quitarla hay que ir a cada data de controller y anexar el item reverse
                    $reverse = isset($config['reverse']);
                @endphp
                <x-table-component :header="$config['table']['header']" :include="$config['table']['include']" :data="$config['data']" :reverse="$reverse">
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
