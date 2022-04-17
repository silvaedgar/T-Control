@extends('layouts.app', ['activePage' => 'clients', 'titlePage' => __('Modulo de Clientes'),
    'enableNavBar' => 'true'])

@section('content')

<div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            <form method="post" action="{{route('clients.update',$client)}}" autocomplete="off" class="form-horizontal">
            @csrf
            @method('put')
            <div class="card ">
              <input type="hidden" value = "{{ $userclient }}" name="userclient">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Editar Cliente') }}</h4>
                <p class="card-category">{{ __('Detalle del Cliente') }}</p>
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
                @include('clients.form')

              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Grabar Cliente') }}</button>
              </div>
              @if ($userclient == "user")
                <a href = "{{ route('clients.index') }}"> {{ __('Volver al listado') }} </a>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection


