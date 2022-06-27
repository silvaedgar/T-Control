@extends('layouts.app', ['activePage' => 'clients', 'titlePage' => __('Modulo de Clientes'), 'enableNavBar' => 'true'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ route('clients.store') }}" autocomplete="off" class="form-horizontal">
                        @csrf
                        <div class="card ">
                            <div class="card-header card-header-primary">
                                @include('shared.header')
                                {{-- <h4 class="card-title">{{ __('Creando Cliente') }}</h4> --}}
                            </div>
                            <div class="card-body ">
                                @include('clients.form')
                            </div>
                            <div class="card-footer ml-auto mr-auto">
                                <button type="submit" class="btn btn-primary">{{ __('Grabar Cliente') }}</button>
                            </div>
                            <a href="{{ route('clients.index') }}"> {{ __('Volver al listado') }} </a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
