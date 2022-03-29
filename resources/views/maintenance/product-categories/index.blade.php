@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __('Modulo Categorias de Productos')])

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
                    <h4 class="card-title ">Listado de Categorias por Grupo de Productos</h4>
                </div>
                <div class="col-3 justify-end">
                    <a href="{{route('maintenance.productcategories.create')}}">
                        <button class="btn btn-info"> Crear Categoria de Producto
                            <i class="material-icons" aria-hidden="true">person_add</i>
                        </button> </a>
                </div>
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped" id="category" style="width: 100%">
                <thead class=" text-primary">
                    <th>Item</th>
                    <th>Grupo</th>
                    <th>Descripcion</th>
                    <th>Acción</th>
                </thead>
                <tbody>
                    @foreach ($productcategories as $category)
                    <tr style="height: 1%">
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $category->ProductGroup->description }} </td>

                        <td> {{ $category->description }} </td>
                        <td>
                            <div class="row">
                                <div class="col-2">
                                    <a href="{{route('maintenance.productcategories.edit',$category->id)}}">
                                        <button class="btn-sm btn-danger" data-bs-toggle="tooltip" title="Editar Forma de Pago">
                                            <i class="fa fa-edit"></i> </button> </a>
                                </div>
                                <div class="col-2">
                                    <form action="{{ route('maintenance.productcategories.destroy',$category->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                           <button class="btn-sm btn-danger"  data-bs-toggle="tooltip" title="Eliminar Categoria de Producto"
                                                    onclick = "return DeleteRecord('Seguro que desea eliminar la Categoria del Grupo?')">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                    </form>
                                </div>
                            </div>
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
    {{-- <script src="https://code1.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script> --}}
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js')}}/functions.js"> </script>
    <script>
        $(document).ready(function() {
            $('#category').DataTable({
                lengthMenu : [[5,10,15,-1],[5,10,20,"All"]],
                responsive : true,
                autoWidth : false
            });
        });
     </script>
@endpush
