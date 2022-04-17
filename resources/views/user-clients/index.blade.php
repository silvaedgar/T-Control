@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Modulo de Usuarios Clientes')])


@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                    <h4 class="card-title ">Clientes por Confirmar Usuarios en el Sistema</h4>
                </div>
              </div>
          </div>
          <div class="card-body">
            <form action="{{ route('userclients.store') }}" method="post">
                @csrf
                <div class="table-responsive">
                    <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
                        <thead class=" text-primary">
                            <tr>
                                <th>Item</th>
                                <th>Nombre Usuario</th>
                                <th>e-mail</th>
                                <th>Asignar Clientes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td> {{ $loop->iteration }}</td>
                                <td>  {{ $user->name }}
                                    <input type="hidden" name="user_id[]" value = "{{ $user->id}}"> </td>
                                <td> {{ $user->email}} </td>
                                <td> <select name="client_id[]" id="clients-select{{$loop->iteration}}">
                                        <option value = "0"> Seleccione un cliente ... </option>
                                        @foreach ($clients as $client)
                                            <option value = {{ $client->id}}> {{ $client->names }} </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                                </form>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer mx-auto">
                    <button type="submit" class="btn btn-primary">{{ __('Procesar Datos') }}</button>
                </div>
            </form>
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

