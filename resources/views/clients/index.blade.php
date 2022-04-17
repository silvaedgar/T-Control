@extends('layouts.app', ['class'=> 'bg-info','activePage' => 'sales', 'titlePage' => __('Modulo de Clientes')])

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    {{-- <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet"> --}}
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">


@endsection


@section('content')
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
              <div class="row">
                <div class="col-xl-5 col-md-4 col-sm-3 align-middle">
                    <h4 class="card-title ">Listado de Clientes</h4>
                </div>
                <div class="col-xl-7 col-md-8 col-sm-9">
                    <a href = "" target="_blank">
                        <button class="btn btn-info">Listado de Clientes
                            <i class="material-icons" aria-hidden="true">print</i>
                        </button> </a>

                    <a href = "{{ route('clients.listdebtor')}}" target="_blank">
                        <button class="btn btn-info">Deudores
                            <i class="material-icons" aria-hidden="true">print</i>
                        </button> </a>

                    <a href="{{route('clients.create')}}">
                        <button class="btn btn-info"> Crear Cliente
                            <i class="material-icons" aria-hidden="true">person_add</i>
                        </button> </a>
                </div>
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
                <thead class=" text-primary">
                    <th>Item</th>
                    <th>Ci/Rif</th>
                    <th>Nombres</th>
                    <th>Saldo</th>
                    <th>Acción</th>
            </thead>
                <tbody>
                    @foreach ($clients as $client)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $client->document_type }}-{{$client->document}} </td>
                        <td> {{ $client->names }} </td>
                        <td> {{ $client->balance }} {{ $symbolcoin->symbol }} </td>
                        <td>
                            <a href="{{route('clients.edit',$client->id)}}">
                                <button class="btn-info" data-bs-toggle="tooltip" title="Editar Cliente">
                                <i class="fa fa-edit"></i> </button>
                            </a>
                            <input type="hidden" id="message-item-delete" value = " Al Cliente: {{ $client->names}}">
                            <form action="{{ route('clients.destroy',$client)}}"  id="delete-item" method="post"
                                    class = "d-inline">
                                @csrf
                                @method('delete')
                                <button type = "submit" class="btn-danger" data-bs-toggle="tooltip" title="Eliminar Cliente">
                                <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </form>
                            <a href="{{route('clients.balance',$client->id)}}">
                                <button class="btn-primary" data-bs-toggle="tooltip" title="Ver Movimientos">
                                    <i class="fa fa-money" aria-hidden="true"></i>
                                </button> </a>
                       </td>
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
    <script src="{{ asset('js')}}\globalvars.js"></script>
@endpush

