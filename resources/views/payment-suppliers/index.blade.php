@extends('layouts.app', ['activePage' => 'purchases', 'titlePage' => __('Modulo de Pago a Proveedores'),
    'enableNavBar' => 'true'])

@section('css')
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
  <div class="content">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header card-header-primary">
              <div class="row">
                <div class="col-md-7 col-xl-8 col-sm-6 align-middle">
                    <h4 class="card-title ">Listado de Pago realizados a Proveedores</h4>
                </div>
                <div class="col-md-5 col-xl-3 col-sm-6 justify-end">
                    <a href="{{route('paymentsuppliers.create')}}">
                        <button class="btn btn-info"> Generar Pago a Proveedor
                            <i class="material-icons" aria-hidden="true">person_add</i>
                        </button> </a>
                </div>
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped" id="suppliers" style="width: 100%">
                <thead class=" text-primary">
                    <th>Item</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Forma de Pago</th>
                </thead>
                <tbody>
                    @foreach ($paymentsuppliers as $paymentsupplier)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $paymentsupplier->Supplier->name }} </td>
                        <td> {{ $paymentsupplier->payment_date }} </td>
                        <td> {{ $paymentsupplier->mount }} ({{$paymentsupplier->Coin->symbol }}) </td>
                        <td> {{ $paymentsupplier->PaymentForm->description }} </td>
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
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#suppliers').DataTable({
                lengthMenu : [[5,10,15,-1],[5,10,20,"All"]],
                responsive : true,
                autoWidth : false
            });
        });

    </script>

@endpush


