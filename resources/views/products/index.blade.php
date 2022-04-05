@extends('layouts.app', ['activePage' => 'store', 'titlePage' => __('Modulo de Productos'),
    'enableNavBar' => 'true'])

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
  <div class="content">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header card-header-primary">
              <div class="row">
                <div class="col-xl-7 col-md-5 col-sm-3 align-middle">
                    <h4 class="card-title ">Listado de Productos</h4>
                </div>
                <div class="col-xl-5 col-md-7 col-sm-9">
                    <a href = "{{ route('products.listprint')}}">
                    <button class="btn btn-info">Generar PDF
                        <i class="material-icons" aria-hidden="true">print</i>
                    </button> </a>
                    <a href="{{route('products.create')}}">
                        <button class="btn btn-info float-right "> Crear Producto
                            <i class="material-icons" aria-hidden="true">person_add</i>
                        </button> </a>
                </div>
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped" id="products" style="width: 100%">
                <thead class=" text-primary">
                    <th>Item</th>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Grupo</th>
                    <th>Categoria</th>
                    <th>Precio Vta</th>
                    <th>Precio Costo</th>
                    <th>Acción</th>
            </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr style="font-size: small">
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $product->code }} </td>
                        <td> {{ $product->name }} </td>
                        <td> {{ $product->description }} </td>
                        <td> {{ $product->category }} </td>
                        <td> {{ $product->sale_price }} {{ $symbolcoin[1]->symbol }} </td>
                        <td> {{ $product->cost_price }} {{ $symbolcoin[0]->symbol }} </td>
                        <td>
                            <a href="{{route('products.edit',$product->id)}}">
                                <button class="btn-sm btn-danger" data-bs-toggle="tooltip" title="Editar Producto">
                                <i class="fa fa-edit"></i> </button> </a>
                            <input type="hidden" id="message-item-delete" value = " Al Producto: {{ $product->name}}">
                            <form action="{{ route('products.destroy',$product->id)}}"  method="post"
                                    class = "d-inline" id="delete-item">
                                @csrf
                                @method('delete')
                                <button class="btn-sm btn-danger"  data-bs-toggle="tooltip" title="Eliminar Producto">
                                <i class="fa fa-trash-o"></i></button>
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
@endsection

@push('js')
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js')}}\globalvars.js"></script>
    <script>
        $(document).ready(function() {
            $('#products').DataTable({
                lengthMenu : [[5,10,15,-1],[5,10,20,"All"]],
                responsive : true,
                autoWidth : false
            });
        });
    </script>
@endpush



