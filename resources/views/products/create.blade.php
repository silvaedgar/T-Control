@extends('layouts.app', ['activePage' => 'store', 'titlePage' => __('Modulo de Productos')])
@section('css')
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection
@section('content')
    <div class="content" style="margin-top:40px">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ route('products.store') }}" autocomplete="off" class="form-horizontal"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card ">
                        <div class="card-header card-header-primary">
                            @include('shared.header')
                        </div>
                        <div class="card-body ">
                            @include('products.form')
                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Grabar Producto') }}</button>
                        </div>
                        <a href="{{ route('products.index') }}"> {{ __('Volver al listado') }} </a>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js') }}/searchfunctions.js"></script>
    <script src="{{ asset('js') }}/globalvars.js"></script>
@endpush
