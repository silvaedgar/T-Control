@extends('layouts.app', ['activePage' => $config['layout']['activePage'], 'titlePage' => __($config['layout']['titlePage'])])

@section('css')
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                @if ($config['data']['collection'] != null)
                    <form action="{{ route($config['router']['routePost'], $config['router']['item']) }}" autocomplete="off"
                        class="form-horizontal" method="post">
                        @method('put')
                    @else
                        <form action="{{ route($config['router']['routePost']) }}" autocomplete="off" class="form-horizontal"
                            method="post">
                @endif
                @csrf
                <input name="id" type="hidden"
                    value="{{ old('id', $config['data']['collection'] != null ? $config['data']['collection']->id : 0) }}">
                <input name="user_id" type="hidden"
                    value="{{ old('user_id', $config['data']['collection'] != null ? $config['data']['collection']->user_id : auth()->user()->id) }}">
                <div class="card mx-auto">
                    <div class="card-header card-header-primary">
                        <x-header-create-edit :data="$config['header']"></x-header-create-edit>
                    </div>
                    <div class="card-body">
                        @include($config['header']['form'])
                    </div>
                    <div class="card-footer ml-auto mr-auto">
                        <button class="btn btn-primary" type="submit">{{ __('Grabar') }}</button>
                    </div>
                    </form>
                    <a href="{{ route($config['router']['routeIndex']) }}"> {{ __('Volver al listado') }} </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            let group = @json($config['data']);
            if (group.collection.group_id) // cuando este con las categorias
                document.getElementById('group_id').value = group.collection.group_id
        })
    </script>
@endpush
