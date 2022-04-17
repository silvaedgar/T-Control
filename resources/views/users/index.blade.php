@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('Modulo de Usuarios')])


@section('css')
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet"> --}}
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
                    <h4 class="card-title ">Listado de Usuarios del Sistema</h4>
                </div>
                <div class="col-3 justify-end">
                    <a href="{{route('users.create')}}">
                        <button class="btn btn-info"> Crear Usuario
                            <i class="material-icons" aria-hidden="true">person_add</i>
                        </button> </a>
                </div>
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
                <thead class=" text-primary">
                    <tr>
                        <th>Item</th>
                        <th>Nombre Usuario</th>
                        <th>e-mail</th>
                        <th> Roles </th>
                        <th> Acción </th>
                    </tr>
                </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td> {{ $loop->iteration }}</td>
                            <td>  {{ $user->name }} </td>
                            <td> {{ $user->email}} </td>
                            <td >
                                @forelse ($user->roles as $rol)
                                   <span class="text-dark "> {{ $rol->name }} </span>
                                   @if ($rol->name == 'Client' && $user->names <> '')
                                    <span style="color: red"> Asociado a: {{ $user->names }} </span>
                                   @endif
                                @empty
                                    <span class=" text-danger "> No tiene rol asignado </span>
                                @endforelse
                            </td>
                            <td>
                                <a href="{{route('users.edit',$user->id)}}">
                                    <button class="btn-info" data-bs-toggle="tooltip" title="Editar Usuario">
                                    <i class="fa fa-edit"></i> </button> </a>
                                <input type="hidden" id="message-item-delete" value = " Al usuario: {{ $user->name}}">
                                {{-- <form action="{{ route('users.destroy',$user->id)}}"  method="post"
                                        class = "d-inline" id="delete-item">
                                    @csrf
                                    @method('delete')
                                    <button class="btn-danger"  data-bs-toggle="tooltip" title="Eliminar Usuario">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                </form> --}}
                            </td>
                        </tr>

                        @endforeach
                    </tbody>

{{--                <th>Item</th>
                    <th>Rif/Ci</th>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Saldo</th>
                    <th>Acción</th>
            </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $user->document_type }}-{{$user->document}} </td>
                        <td> {{ $user->name }} </td>
                        <td> {{ $user->contact }} </td>
                        <td> {{ $user->balance }} </td>
                        <td>
                            <a href="{{route('users.edit',$user->id)}}">
                                <button class="btn-sm btn-danger" data-bs-toggle="tooltip" title="Editar Usuario">
                                <i class="fa fa-edit"></i> </button> </a>
                            <input type="hidden" id="message-item-delete" value = " Al Usuario: {{ $user->name}}">
                            <form action="{{ route('users.destroy',$user->id)}}"  method="post"
                                    class = "d-inline" id="delete-item">
                                @csrf
                                @method('delete')
                                <button class="btn-sm btn-danger"  data-bs-toggle="tooltip" title="Eliminar Usuario">
                                <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </form>
                       </td>
                   </tr>
                    @endforeach
                </tbody> --}}


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
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{asset('js')}}/globalvars.js"></script>
@endpush

