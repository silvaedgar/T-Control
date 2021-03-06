@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __('Modulo de Unidad de Medida')])
@section('css')
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection
@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{route('maintenance.unitmeasures.store')}}" autocomplete="off" class="form-horizontal">
            @csrf

            <div class="card mx-auto">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Crear Unidad de Medida') }}</h4>
                <p class="card-category">{{ __('Detalle de la Informacion') }}</p>
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
                @include('maintenance.unit-measures.form')
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Grabar Unidad') }}</button>
              </div>
              <a href = "{{ route('maintenance.unitmeasures.index') }}"> {{ __('Volver al listado') }} </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection


