@extends('layouts.app', ['activePage' => 'purchases', 'titlePage' => __('Modulo de Proveedores'), 'enableNavBar' => 'true'])


@section('css')
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content" style="margin-top:40px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            @include('shared.header')
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
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
                                                <td> {{ $supplier->document_type }}-{{ $supplier->document }} </td>
                                                <td> {{ $supplier->name }} </td>
                                                <td> {{ $supplier->contact }} </td>
                                                <td> {{ $supplier->balance }} {{ $data_common['base_coin_symbol'] }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('suppliers.edit', $supplier->id) }}">
                                                        <button class="btn-info" data-bs-toggle="tooltip"
                                                            title="Editar Proveedor">
                                                            <i class="fa fa-edit"></i> </button> </a>
                                                    <input type="hidden" id="message-item-delete"
                                                        value=" Al Proveedor: {{ $supplier->name }}">
                                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}"
                                                        method="post" class="d-inline delete-item">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn-danger" data-bs-toggle="tooltip"
                                                            title="Eliminar Proveedor">
                                                            <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                    </form>
                                                    <a href="{{ route('suppliers.balance', $supplier->id) }}">
                                                        <button class="btn-primary" data-bs-toggle="tooltip"
                                                            title="Ver Movimientos">
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
    </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js') }}/globalvars.js"></script>
@endpush
