@extends('layouts.app', ['activePage' => 'sales', 'titlePage' => __('Modulo de Clientes')])


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
                <div class="col-xl-3 col-md-3 col-sm-4">
                    <h4 class="card-title "> Movimientos </h4>
                </div>
                <div class="col-xl-4 col-md-5 col-sm-8">
                    <h5> Cliente:  {{ $movements[0]->names }}
                        <a href="{{ route ('clients.printbalance',[$movements[0]->client_id,$mensaje])}}" target="_blank" class="text-white"> <button type="button"
                                    class="bg-info" data-toggle="tooltip" data-placement="top" title="Imprimir Balance">
                            <i class="fa fa-print" aria-hidden="true"></i>
                        </button> </a> </h5>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <h5> Saldo: {{ $movements[0]->balance}} {{$movements[0]->symbol}} </h5>
                </div>
                @if (isset($backlist))
                    <div class="col-sm-6 col-md-11 col-xl-2 float-right ">
                        <a class="text-white " href = "{{ route('clients.index') }}"> {{ __('Volver al listado') }} </a>
                    </div>
                @endif
              </div>
          </div>
          <div class="card-body">
            <div class="container mx-auto" style="width:15rem">
                <a href="{{ route('clients.balance',[$id,$mensaje])}}" class="text-danger">
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
                        echo $balance;
                    @endphp
                    @if ($movements[0]->type != 'Balance')
                        @foreach ($movements as $movement)
                        <tr>
                            <td> {{ $loop->iteration }} </td>
                            <td> {{ date("d-m-Y",strtotime($movement->date))  }} </td>
                            <td> {{ ($movement->type == 'Compras' ? number_format($movement->mountbalance,2).' ('.$movement->symbol.')' : '') }}
                                {{ ($movement->symbol == '$' && $movement->type == 'Compras' ? '    ('.(number_format($movement->mount,2)).' $)' : '') }} </td>
                            </td>
                            <td> {{ ($movement->type == 'Pagos' ?  number_format($movement->mountbalance,2) : '') }}
                                {{ ($movement->symbol == '$' && $movement->type == 'Pagos' ? '    ('.(number_format($movement->mount,2))." $movement->symbol )" : '') }} </td>
                            <td>
                                {{ number_format($balance,2) }} (BsD)
                                @php
                                    $balance = ($movement->type == 'Pagos' ? $balance + $movement->mountbalance : $balance - $movement->mountbalance);
                                @endphp
                            </td>
                            <td>
                                @if ($movement->type == 'Compras')
                                    <a href = "{{ route('sales.show',$movement->id)}}">
                                        <button class="btn-info" data-bs-toggle="tooltip"
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
                            <td> {{ number_format($balance,2) }} (BsD) </td>
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

