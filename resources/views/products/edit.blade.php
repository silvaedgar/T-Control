@extends('layouts.app', ['activePage' => 'store', 'titlePage' => __('Modulo de Productos')])
@section('css')
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('products.update', $data['product']) }}" autocomplete="off"
                        class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="card ">
                            <div class="card-header card-header-primary">
                                @include('shared.header')
                            </div>
                            <div class="card-body ">
                                @include('products.form')
                            </div>
                            <div class="card-footer">
                                @if (count($data['purchases']) > 0)
                                    <div class="col-sm-4" style="top: -100px">
                                        <div class="row ml-5 ">
                                            <button type="submit"
                                                class="btn btn-primary">{{ __('Grabar Producto') }}</button>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        @include('products.last_purchases')
                                    </div>
                                @else
                                    <div class="mx-auto">
                                        <button type="submit" class="btn btn-primary">{{ __('Grabar Producto') }}</button>
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('products.index') }}"> {{ __('Volver al listado') }} </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js') }}/globalvars.js"></script>
    <script src="{{ asset('js') }}/searchfunctions.js"></script>
@endpush
