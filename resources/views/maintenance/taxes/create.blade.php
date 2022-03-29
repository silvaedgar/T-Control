@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __(('Modulo de Impuestos'))])
@section('css')
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection
@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{route('maintenance.taxes.store')}}" autocomplete="off" class="form-horizontal">
            @csrf

            <div class="card mx-auto">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Crear Impuesto') }}</h4>
                <p class="card-category">{{ __('Detalle del Impuesto') }}</p>
              </div>
              <div class="card-body" >
                @if (session('status'))
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="material-icons">close</i>
                        </button>
                        <span>{{ session('status') }}</span>
                      </div>
                    </div>
                  </div>
                @endif
                @include('maintenance.taxes.form')
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Grabar Impuesto') }}</button>
            </div>
          </form>
          <a href = "{{ route('maintenance.taxes.index') }}"> {{ __('Volver al listado') }} </a>
        </div>
      </div>
    </div>
  </div>
@endsection


