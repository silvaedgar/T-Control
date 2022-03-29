@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __('Modulo Grupos de Productos')])
@section('css')
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection
@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">

          <form method="post" action="{{route('maintenance.productcategories.store')}}" autocomplete="off" class="form-horizontal">
            @csrf

            <div class="card mx-auto">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Crear Categoria del Grupo') }}</h4>
                <p class="card-category">{{ __('Detalle de la Categoria') }}</p>
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
                @include('maintenance.product-categories.form')
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Grabar Categoria') }}</button>
            </div>
          </form>
          <a href = "{{ route('maintenance.productcategories.index') }}"> {{ __('Volver al listado') }} </a>
        </div>
      </div>
    </div>
  </div>
@endsection


