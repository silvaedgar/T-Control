@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __('Modulo de Monedas')])

@section('css')
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <form method="post" action="{{route('maintenance.currencyvalues.store')}}" autocomplete="off" class="form-horizontal">
            @csrf
            <div class="card mx-auto">
              <div class="card-header card-header-primary">
                <h4 class="card-title">{{ __('Establecer Precio Compra Venta') }}</h4>
                <p class="card-category">{{ __('Moneda base de calculo: ') }} {{$base_currency->name}}
                        </p>
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
                @include('maintenance.currency-values.form')
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Grabar Relación ') }}</button>
            </div>
          </form>
          <a href = "{{ route('maintenance.coins.index') }}"> {{ __('Volver al listado') }} </a>
        </div>
      </div>
    </div>
  </div>
@endsection


