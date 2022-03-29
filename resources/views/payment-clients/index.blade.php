@extends('layouts.app', ['activePage' => 'sales', 'titlePage' => __('Modulo de Pago de Clientes')])

@section('css')
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection


@section('content')
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
              <div class="row">
                <div class="col-8 align-middle">
                    <h4 class="card-title ">Listado de Pago realizados por Clientes</h4>
                </div>
                <div class="col-3 justify-end">
                    <a href="{{route('paymentclients.create')}}">
                        <button class="btn btn-info"> Generar Pago de Cliente
                            <i class="material-icons" aria-hidden="true">person_add</i>
                        </button> </a>
                </div>
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped" id="clients" style="width: 100%">
                <thead class=" text-primary">
                    <th>Item</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Forma de Pago</th>
                </thead>
                <tbody>
                    @foreach ($paymentclients as $paymentclient)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $paymentclient->Client->names }} </td>
                        <td> {{ $paymentclient->payment_date }} </td>
                        <td> {{ $paymentclient->mount }} ({{$paymentclient->Coin->symbol }}) </td>
                        <td> {{ $paymentclient->PaymentForm->description }} </td>
                   </tr>
                    @endforeach
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
    {{-- <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script> --}}
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#clients').DataTable({
                lengthMenu : [[5,10,15,-1],[5,10,20,"All"]],
                responsive : true,
                autoWidth : false
            });
        });

    </script>

@endpush


