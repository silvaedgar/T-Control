@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __('Modulo de Monedas')])

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection


@section('content')
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
              <div class="row">
                <div class="col-8 align-middle">
                    <h4 class="card-title ">Relacion Valor Costo-Venta de Monedas</h4>
                    <p class="card-category">{{ __('Moneda Base: ')  }}{{ $base_currency->name }} ({{$base_currency->symbol}})</p>
                </div>
                <div class="col-3 justify-end">
                    <a href="{{route('maintenance.currencyvalues.edit',$base_currency->id)}}">
                        <button class="btn btn-info"> Crear Valor Compra-Venta
                            <i class="material-icons" aria-hidden="true">person_add</i>
                        </button> </a>
                </div>
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped" id="coins" style="width: 100%">
                <thead class=" text-primary">
                    <th>Item</th>
                    <th> Fecha </th>
                    <th>Moneda</th>
                    <th>Precio Compra</th>
                    <th>Precio Venta</th>
                </thead>
                <tbody>
                    @foreach ($coinvalues as $coin)
                    <tr style="height: 1%">
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $coin->date_value }} </td>
                        <td> {{ $coin->Coin->name }} ({{ $coin->Coin->symbol }}) </td>
                        <td> 1{{$base_currency->symbol}}  =  {{ $coin->Coin->symbol }} {{ number_format($coin->purchase_price,2) }} </td>
                        <td> 1{{$base_currency->symbol}} = {{ $coin->Coin->symbol }} {{ number_format($coin->sale_price,2) }} </td>
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
</div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#coins').DataTable({
                lengthMenu : [[5,10,15,-1],[5,10,20,"All"]],
                responsive : true,
                autoWidth : false
            });
        });
     </script>
@endpush
