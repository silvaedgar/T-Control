@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Inicio')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="card">
            <div class="card-header text-center ">
                <span class="text-dark font-bold  h4"> Modulo de Control de Tiendas (T-Control).
                    Diseñado para automatizar el proceso de control de Pagos y Facturas de pequenas tiendas y bodegas </span>
            </div>
          </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
    });
  </script>
@endpush
