@extends('layouts.app', ['activePage' => 'sales', 'titlePage' => __('Modulo de Ventas')])

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content" style="margin-top:40px">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    {{-- {{ dd($data_common) }} --}}
                    <div class="card-header card-header-primary">
                        <div class="row">
                            {{-- @include('shared.header') --}}
                            <div class="col-sm-4 col-md-6 col-xl-7 align-middle">
                                <h4 class="card-title ">Facturas de Ventas</h4>
                                <a style="color: #99ffff; font-size: 12px" href="{{ route('paymentclients.create') }}">
                                    Generar Pago de Cliente </a>

                            </div>
                            <div class="col-sm-8 col-md-6 col-xl-5 ">
                                <form action="{{ route('sales.filter') }}" method="post" class="d-inline"
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
                                <a href="{{ route('sales.create') }}">
                                    <button class="btn btn-info"> Crear Factura de Venta
                                        <i class="material-icons" aria-hidden="true">person_add</i>
                                    </button> </a>
                            </div>
                        </div>
                    </div>
                    <form class="mt-3" method="POST" action="{{ route('sales.filter') }}">
                        @csrf
                        @include('shared.filter')
                    </form>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
                                <thead class=" text-primary">
                                    <th>Item</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Saldo Pendiente</th>
                                    <th>Status</th>
                                    <th>
                                    <th>
                                </thead>
                                <tbody>
                                    @foreach ($sales as $sale)
                                        <tr
                                            class="{{ $sale->status != 'Parcial' && $sale->status != 'Pendiente' ? 'bg-warning' : '' }}">
                                            <td><a href="{{ route('sales.show', $sale->id) }}"> {{ $loop->iteration }}
                                                </a> </td>
                                            <td> {{ date('d-m-Y', strtotime($sale->sale_date)) }} </td>
                                            <td> {{ $sale->Client->names }} </td>
                                            <td> {{ $sale->mount }}({{ $sale->Coin->symbol }}) </td>
                                            <td> {{ $sale->mount - $sale->paid_mount }}({{ $sale->Coin->symbol }})
                                            </td>

                                            <td> {{ $sale->status == 'Parcial' ? 'Parcialmente Cancelada' : $sale->status }}
                                            </td>
                                            <td> <a href="{{ route('sales.show', $sale->id) }}">
                                                    <button class="btn-info" data-bs-toggle="tooltip" title="Ver Factura">
                                                        <i class="fa fa-table" aria-hidden="true"></i> </button>
                                                </a>
                                                <input type="hidden" id="message-item-delete" value=" Anular la Factura ">
                                                @if ($sale->status == 'Pendiente')
                                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="post"
                                                        class="d-inline delete-item">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn-danger" data-bs-toggle="tooltip"
                                                            title="Anular Factura">
                                                            <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                    </form>
                                                @endif
                                            </td>
                                            <td> </td>
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
    <script src="{{ asset('js') }}/globalvars.js"></script>
@endpush
