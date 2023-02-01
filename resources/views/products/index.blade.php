@extends('layouts.app', ['activePage' => 'store', 'titlePage' => __('Modulo de Productos'), 'enableNavBar' => 'true'])

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content" style="margin-top: 40px">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        @include('shared.header')
                        @if (session('message_status'))
                            <div class="bg-info mt-1 font-bold ">
                                {{ session('message_status') }}
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
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
                                        <tr style="font-size: small"
                                            class="{{ $product->status == 'Inactivo' ? 'bg-warning' : '' }}">
                                            <td> {{ $loop->iteration }} </td>
                                            <td> {{ $product->code }} </td>
                                            <td> {{ $product->name }} </td>
                                            <td> {{ $product->ProductCategory->ProductGroup->description }} </td>
                                            <td> {{ $product->ProductCategory->description }} </td>
                                            <td> {{ $product->sale_price }} {{ $data_common['sale_coin_symbol'] }}
                                            <td> {{ $product->cost_price }} {{ $data_common['purchase_coin_symbol'] }}
                                            </td>
                                            <td>
                                                <a href="{{ route('products.edit', $product) }}">
                                                    <button class="btn-info" data-bs-toggle="tooltip"
                                                        title="Editar Producto">
                                                        <i class="fa fa-edit"></i> </button> </a>
                                                <input type="hidden" id="message-item-delete"
                                                    value=" Al Producto: {{ $product->name }}">
                                                <form action="{{ route('products.destroy', $product->id) }}" method="post"
                                                    class="d-inline delete-item">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn-danger" data-bs-toggle="tooltip"
                                                        title="{{ $product->status == 'Inactivo' ? 'Activar Producto' : 'Eliminar Producto' }}">
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
    <script src="{{ asset('js') }}\globalvars.js"></script>
    <script>
        $('#delete-item').submit(function(e) {
            e.preventDefault();
            let message = document.getElementById('message-item-delete').value
            Swal.fire({
                title: 'Esta Seguro de Eliminar? ',
                text: message,
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.value) {
                    this.submit();
                }
            })
        })
    </script>
@endpush
