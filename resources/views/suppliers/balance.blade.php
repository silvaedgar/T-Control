@extends('layouts.app', ['activePage' => 'purchases', 'titlePage' => __('Modulo de Proveedores')])


@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection


@section('content')
<div class="content">
    <input type="hidden" id="id" value = "{{ $id }}">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
              <div class="row">
                <div class="col-md-5 col-sm-3">
                    <h4 class="card-title ">Detalle de Movimientos </h4>
                </div>
                <div class="col-sm-5">
                    <h5> Proveedor: <span id="mountlabel"> {{ $movements[0]->name }}  </h5>
                </div>
                <div class="col-sm-4 col-xl-2 float-right ">
                    <a class="text-white " href = "{{ route('suppliers.index') }}"> {{ __('Volver al listado') }} </a>
                </div>
              </div>
          </div>
          <div class="card-body">
              <div class="container mx-auto" style="width:15rem">
                    <a href="{{ route('suppliers.balance',[$id,$mensaje])}}" class="text-danger">
                        {{ $mensaje }} </a>
              </div>
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
                <thead class=" text-primary">
                    <th>Item</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Abono</th>
                    <th>Saldo</th>
                    <th></th>
            </thead>
                <tbody>
                     @php
                        $balance = $movements[0]->balance;
                    @endphp
                    @if ($movements[0]->type != 'Balance')
                        @foreach ($movements as $movement)
                        <tr>
                            <td> {{ $loop->iteration }} </td>
                            <td> {{ date("d-m-Y",strtotime($movement->date))  }} </td>
                            <td> {{ ($movement->type == 'Compras' ? number_format($movement->mountbalance,2).' ('.$movement->symbol.')' : '') }}
                                {{ ($movement->symbol != '$' && $movement->type == 'Compras' ? '    ('.(number_format($movement->mount,2)).' Bs)' : '') }} </td>
                                </td>
                            <td> {{ ($movement->type == 'Pagos' ?  number_format($movement->mountbalance,2) : '') }}
                                {{ ($movement->symbol != '$' && $movement->type == 'Pagos' ? '    ('.(number_format($movement->mount,2))." $movement->symbol )" : '') }} </td>
                            <td>
                                {{ number_format($balance,2) }} ($)
                                @php
                                    $balance = ($movement->type == 'Pagos' ? $balance + $movement->mountbalance : $balance - $movement->mountbalance);
                                @endphp
                            </td>
                            <td>
                                @if ($movement->type == 'Compras')
                                    <a href = "{{ route('purchases.show',$movement->id)}}">
                                        <button class="btn-primary" data-bs-toggle="tooltip"
                                                title="Ver Detalle">
                                            <i class="fa fa-money" aria-hidden="true"></i>
                                        </button> </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endif
                    {{-- Fila del Balance Inicial en caso de ser diferemte de 0 --}}
                    @if ($balance != 0)
                        <tr>
                            <td> {{ (count($movements) == 1 ? 1 : count($movements)  + 1) }} </td>
                            <td>  Balance Inicial </td>
                            <td>  {{ $movements[0]->name }} </td>
                            <td>  </td>
                            <td> {{ number_format($balance,2) }} ($) </td>
                            <td> </td>
                        </tr>
                    @endif

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
    <script src="{{asset('js')}}/globalvars.js"></script>

@endpush

