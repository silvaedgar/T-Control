@extends('layouts.app', ['activePage' => 'purchases', 'titlePage' => __('Modulo de Proveedores')])

@section('css')
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('suppliers.store') }}" autocomplete="off" class="form-horizontal">
                        @csrf
                        <div class="card ">
                            <div class="card-header card-header-primary">
                                @include('shared.header')
                                {{-- <h4 class="card-title">{{ __('Creando Proveedor') }}</h4> --}}
                            </div>
                            <div class="card-body ">
                                @include('suppliers.form')
                            </div>
                            <div class="card-footer ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary">{{ __('Grabar Proveedor') }}</button>
                            </div>
                            <a href="{{ route('suppliers.index') }}"> {{ __('Volver al listado') }} </a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
