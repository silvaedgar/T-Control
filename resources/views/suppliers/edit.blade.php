@extends('layouts.app', ['activePage' => 'purchases', 'titlePage' => __('Modulo de Proveedores'), 'enableNavBar' => 'true'])
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection
@section('content')
    <div class="content">

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('suppliers.update', $supplier) }}" autocomplete="off"
                        class="form-horizontal">
                        @csrf
                        @method('put')
                        <div class="card ">
                            <div class="card-header card-header-primary">
                                @include('shared.header')
                                {{-- <h4 class="card-title">{{ __('Editar Proveedor') }}</h4>
                <p class="card-category">{{ __('Detalle del Proveedor') }}</p> --}}
                            </div>
                            <div class="card-body ">
                                @if (session('status'))
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="alert alert-success">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <i class="material-icons">close</i>
                                                </button>
                                                <span>{{ session('status') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
