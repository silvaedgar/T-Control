@extends('layouts.app', ['activePage' => 'purchases', 'titlePage' => __('Modulo de Proveedores'),
            'enableNavBar' => 'true'])


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
                    <h4 class="card-title ">Listado Proveedores</h4>
                </div>
                <div class="col-3 justify-end">
                    <a href="{{route('suppliers.create')}}">
                        <button class="btn btn-info"> Crear Proveedor
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
                    <th>Rif/Ci</th>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Saldo</th>
                    <th>Acción</th>
            </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $supplier->document_type }}-{{$supplier->document}} </td>
                        <td> {{ $supplier->name }} </td>
                        <td> {{ $supplier->contact }} </td>
                        <td> {{ $supplier->balance }} </td>
                        <td>
                            <a href="{{route('suppliers.edit',$supplier->id)}}">
                                <button class="btn-sm btn-danger" data-bs-toggle="tooltip" title="Editar Proveedor">
                                <i class="fa fa-edit"></i> </button> </a>
                            <input type="hidden" id="message-item-delete" value = " Al Proveedor: {{ $supplier->name}}">
                            <form action="{{ route('suppliers.destroy',$supplier->id)}}"  method="post"
                                    class = "d-inline" id="delete-item">
                                @csrf
                                @method('delete')
                                <button class="btn-sm btn-danger"  data-bs-toggle="tooltip" title="Eliminar Proveedor">
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
    {{-- <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script> --}}
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{asset('js')}}/functions.js"></script>
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
