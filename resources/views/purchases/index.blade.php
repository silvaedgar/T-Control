@extends('layouts.app', ['activePage' => 'purchases', 'titlePage' => __('Modulo de Compras'), 'enableNavBar' => 'true'])

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection


@section('content')
    <div class="content" style="margin-top: 40px">
        {{-- <div class="container-fluid"> --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-sm-4 col-md-6 col-xl-7 align-middle">
                                <h4 class="card-title ">Facturas de Compras</h4>
                                <a style="color: #99ffff; font-size: 12px" href="{{ route('paymentsuppliers.create') }}">
                                    Generar Pago a Proveedor </a>
                            </div>
                            <div class="col-sm-8 col-md-6 col-xl-5 ">
                                <form action="{{ route('purchases.filter') }}" method="post" class="d-inline"
                                    target="_blank">
                                    @csrf
                                    <input type="hidden" value="{{ $data_common['data_filter']['status'] }}"
                                        name="status">
                                    <input type="hidden" value="{{ $data_common['data_filter']['date_start'] }}"
                                        name="startdate">
                                    <input type="hidden" value="{{ $data_common['data_filter']['date_end'] }}"
                                        name="enddate">
                                    <input type="hidden" value="Report" name="option">

                                    <button class="btn btn-info" type="submit">Reporte
                                        <i class="material-icons" aria-hidden="true">print</i>
                                    </button>
                                </form>
                                <a href="{{ route('purchases.create') }}">
                                    <button class="btn btn-info"> Crear Factura de Compra
                                        <i class="material-icons" aria-hidden="true">person_add</i>
                                    </button> </a>
                            </div>
                        </div>
                        @if (session('message_status'))
                            @include('shared.message-session')
                        @endif

                    </div>
                    <form class="mt-3" method="POST" action="{{ route('purchases.filter') }}">
                        @csrf
                        @include('shared.filter')
                    </form>
                    <div class="card-body ">
                        <div class="table-responsive ">
                            <table class="table-sm table-hover table-striped w-100" id="data-table">
                                <thead class=" text-primary">
                                    <th>Item</th>
                                    <th>Proveedor</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Saldo Pendiente </th>
                                    <th> Status</th>
                                    <th> Accion
                                    <th>
                                </thead>
                                <tbody>
                                    @foreach ($purchases as $purchase)
                                        <tr
                                            class=" @switch($purchase->status)
                                                        @case('Anulada')
                                                            bg-danger
                                                            @break
                                                        @case('Historico')
                                                            bg-warning
                                                            @break
                                                        @default
                                                            ''
                                                    @endswitch">
                                            <td> {{ $loop->iteration }} </td>
                                            <td> {{ $purchase->Supplier->name }} </td>
                                            <td> {{ date('d-m-Y', strtotime($purchase->purchase_date)) }} </td>
                                            <td> {{ $purchase->mount }}({{ $purchase->Coin->symbol }}) </td>
                                            <td> {{ $purchase->mount - $purchase->paid_mount }}({{ $purchase->Coin->symbol }})
                                            </td>
                                            <td> {{ $purchase->status == 'Parcial' ? 'Parcialmente Cancelada' : $purchase->status }}
                                            </td>
                                            <td> <a href="{{ route('purchases.show', $purchase->id) }}">
                                                    <button class="btn-info" data-bs-toggle="tooltip" title="Ver Factura">
                                                        <i class="fa fa-table" aria-hidden="true"></i> </button> </a>
                                                <input type="hidden" id="message-item-delete" value=" Anular la Factura ">
                                                @if ($purchase->status == 'Parcial' || $purchase->status == 'Pendiente')
                                                    <form action="{{ route('purchases.destroy', $purchase->id) }}"
                                                        method="post" class="d-inline delete-item">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn-danger" data-bs-toggle="tooltip"
                                                            title="Anular Factura">
                                                            <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('suppliers.balance', $purchase->supplier_id) }}">
                                                    <button class="btn-primary" data-bs-toggle="tooltip"
                                                        title="Ver Movimientos">
                                                        <i class="fa fa-money" aria-hidden="true"></i>
                                                    </button> </a>

                                            </td>
                                            <td>
                                            </td>
                                            {{-- esta columna vacia se usa por el datatable sino da error no se porque --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- </div> --}}
    </div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js') }}/globalvars.js"></script>
@endpush
