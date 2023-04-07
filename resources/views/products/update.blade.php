@extends('layouts.app', ['activePage' => $layout['activePage'], 'titlePage' => __($layout['titlePage'])])

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
                @if(isset(update))
                @method('put')
                @endif
                <div class="card ">
                    <div class="card-header card-header-primary">
                        @include('shared.header')
                    </div>
                    <div class="card-body ">
                        @include('products.form')
                    </div>
                    <div class="card-footer">
                        @if (isset($update))
                        @if (count($data['purchases']) > 0)
                        <div class="col-sm-4" style="top: -100px">
                            <div class="row ml-5 ">
                                <button type="submit" class="btn btn-primary">{{ __('Grabar Producto') }}</button>
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
                        @else
                        <button type="submit" class="btn btn-primary">{{ __('Grabar Producto') }}</button>
                        @endif
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
<script>
    function findGroup(event) {
        let categories = @json($data['categories']);
        let grupo = categories.filter(group => group.id == event.target.value)
        document.getElementById('label-group').value = grupo[0].product_group.description
    }
</script>
@endpush