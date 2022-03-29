@extends('layouts.app', ['activePage' => 'sales', 'titlePage' => __('Modulo de Ventas')])

@section('css')
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
                    <h4 class="card-title ">Listado de Facturas de Ventas</h4>
                </div>
                <div class="col-3 justify-end">
                    <a href="{{route('sales.create')}}">
                        <button class="btn btn-info"> Crear Factura de Venta
                            <i class="material-icons" aria-hidden="true">person_add</i>
                        </button> </a>
                </div>
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped"  id="sales" style="width: 100%">
                <thead class=" text-primary">
                    <th>Item</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Saldo Pendiente</th>
                    <th>Status</th>
                    <th> <th>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr style="font-size: small; cursor: pointer" >
                            <td><a href = "{{ route('sales.show',$sale->id) }}"> {{ $loop->iteration }} </a> </td>
                            <td> {{ $sale->sale_date }} </td>
                            <td> {{ $sale->Client->names }} </td>
                            <td> {{ $sale->mount }}({{$sale->Coin->symbol}}) </td>
                            <td> {{ $sale->mount - $sale->paid_mount }}({{$sale->Coin->symbol}}) </td>

                            <td> {{ ($sale->status == "Parcial"? "Parcialmente Cancelada" : $sale->status) }} </td>
                        <td> <a href = "{{ route('sales.show',$sale->id) }}">
                                <button class="btn-sm btn-danger">Ver Factura</button> </a></td>
                        <td>  </td>
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

            $('#sales').DataTable({
                lengthMenu : [[5,10,15,-1],[5,10,20,"All"]],
                responsive : true,
                autoWidth : false
            });

        });
    </script>
@endpush


