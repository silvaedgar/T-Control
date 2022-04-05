@extends('layouts.app', ['activePage' => 'purchases', 'titlePage' => __('Modulo de Compras'),
    'enableNavBar' => 'true'])

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection


@section('content')
<div class="content">
  {{-- <div class="container-fluid"> --}}
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary  ">
              <div class="row">
                <div class="col-md-7 col-xl-8 col-sm-6 align-middle">
                    <h4 class="card-title ">Listado de Facturas de Compras Generadas</h4>
                </div>
                <div class="col-md-5 col-xl-3 col-sm-6 justify-end">
                    <a href="{{route('purchases.create')}}">
                        <button class="btn btn-info"> Crear Factura de Compra
                            <i class="material-icons" aria-hidden="true">person_add</i>
                        </button> </a>
                </div>
              </div>
          </div>
          <div class="card-body ">
            <div class="table-responsive ">
              <table class="table-sm table-hover table-striped " id="purchases" style="width: 100%">
                <thead class=" text-primary">
                    <th width="3%">Item</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Saldo Pendiente </th>
                    <th> Status</th>
                    <th> Accion <th>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $purchase->Supplier->name }} </td>
                        <td> {{ date("d-m-Y",strtotime($purchase->purchase_date)) }} </td>
                        <td> {{ $purchase->mount }}({{$purchase->Coin->symbol}}) </td>
                        <td> {{ $purchase->mount - $purchase->paid_mount }}({{$purchase->Coin->symbol}})</td>
                        <td> {{ ($purchase->status == "Parcial"? "Parcialmente Cancelada" : $purchase->status) }} </td>
                        <td> <a href = "{{ route('purchases.show',$purchase->id) }}">
                                <button class="btn-sm btn-danger">Ver Detalle</button> </a></td>

                        <td>           </td>
                   </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  {{-- </div> --}}
</div>
@endsection

@push('js')
    {{-- <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script> --}}
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#purchases').DataTable({
                lengthMenu : [[5,10,15,-1],[5,10,20,"All"]],
                responsive : true,
                autoWidth : false
            });
        });

    </script>

@endpush

