@extends('layouts.app', ['activePage' => 'purchases', 'titlePage' => __('Modulo de Usuarioes'),
        'enableNavBar' => 'true'])
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection
@section('content')

<div class="content">

    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{route('users.update',$user)}}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('put')
            <div class="card ">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Editar Usuario') }}</h4>
                <p class="card-category">{{ __('Detalle del Usuario') }}</p>
              </div>
              <div class="card-body ">
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
                @include('users.form')

              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Grabar Usuario') }}</button>
              </div>
            </div>
          </form>
          <a href = "{{ route('users.index') }}"> {{ __('Volver al listado') }} </a>
        </div>
      </div>
    </div>
</div>
@endsection


