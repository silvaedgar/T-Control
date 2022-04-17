@extends('layouts.app', ['class'=> 'bg-info','activePage' => 'dashboard', 'titlePage' => __('Inicio')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="card bg-info" style="width: 75%; margin-left:15%">
            <div class="card-header text-center ">
                <span class="text-dark font-bold h4"> El Sistema de Control de Tiendas (T-Control).
                    Es un Modulo Automatizado Diseñado el proceso de control de Pagos y Facturas de pequenas tiendas y bodegas </span>
            </div>
            @if (Auth::user()->hasRole('Client'));
            <div class="card-body">
                <div class="row justify-content-center">
                    <h2> Bienvenido: {{ auth()->user()->name}} </h2>
                </div>
                <div class="row justify-content-center">
                    <h3 class=""> En este Modulo podras consultar tu estado de cuenta con nosotros </h2>
                </div>
                @if (!$exist_client)
                    <div class="row justify-content-center">
                        <h4> Para poder consultar tu estado de cuenta. Debes ser autorizado por el Administrador </h4>
                    </div>
                @endif
            </div>
        @else
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-2 col-md-4">
                            <img src="{{asset('images')}}\clientes.jpeg" alt="Clientes" height="130px"
                                    width ="100%" >
                            <p class="h5 text-black"> Registar tus Clientes y Proveedores para llevar control de los mismos </p>
                        </div>
                        <div class="col-sm-2 col-md-4">
                            <img src="{{asset('images')}}\ctasxpagar.jpeg" alt="Clientes" height="130px"
                                    width ="100%" >
                            <p class="h5 text-black"> Control de Cuentas x Pagar. Registrando tus Compras y Pagos.  </p>
                        </div>
                        <div class="col-sm-2 col-md-4">
                            <img src="{{asset('images')}}\ctasxcobrar.jpeg" alt="Clientes" height="130px"
                                            width ="100%" >
                            <p class="h5 text-black"> Control de Cuentas x Cobrar. Registrando tus Ventas y Abonos efectuados por tus clientes.  </p>
                        </div>

                    </div>
                </div>

            @endif
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
