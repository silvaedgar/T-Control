@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __('Modulo Grupos de Productos')])

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
                    <h4 class="card-title ">Listado de Grupos  de Productos</h4>
                </div>
                <div class="col-3 justify-end">
                    <a href="{{route('maintenance.productgroups.create')}}">
                        <button class="btn btn-info"> Crear Grupo
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
                    <th>Descripcion</th>
                    <th>Acción</th>
                </thead>
                <tbody>
                    @foreach ($productgroups as $group)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $group->description }} </td>
                        <td>
                            <a href="{{route('maintenance.productgroups.edit',$group->id)}}">
                                <button class="btn-info" data-bs-toggle="tooltip"
                                                    title="Editar Grupo de Producto">
                                        <i class="fa fa-edit"></i>
                                </button>
                            </a>

                            <input type="hidden" id="message-item-delete" value = " Al Grupo de Producto: {{ $group->description}}">
                            <form action="{{ route('maintenance.productgroups.destroy',$group->id)}}"  method="post"
                                    class = "d-inline" id="delete-item">
                                @csrf
                                @method('delete')
                                <button class="btn-danger"  data-bs-toggle="tooltip" title="Eliminar Grupo de Producto">
                                <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            </form>
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
</div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js')}}/globalvars.js"> </script>
@endpush
