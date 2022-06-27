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
                <h4 class="card-title">{{ __('Establecer Precio Compra Venta entre Monedas') }}</h4>
                {{-- <p class="card-category">{{ __('Moneda base de calculo: ') }} + $coin_calc_base->name </p> --}}
              </div>
              <div class="card-body" >
                @include('maintenance.currency-values.form')
              </div>
              <div class="card-footer ml-auto mr-auto">
                <button type="submit" class="btn btn-primary">{{ __('Grabar Relacion') }}</button>
            </div>
          </form>
          <a href = "{{ route('maintenance.coins.index') }}"> {{ __('Volver al listado') }} </a>
        </div>
      </div>
    </div>
  </div>
@endsection


