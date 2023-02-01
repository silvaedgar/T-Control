@extends('layouts.app', ['activePage' => 'sales', 'titlePage' => __('Modulo de Pago de Clientes')])

@section('css')
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content" style="margin-top: 40px">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-sm-4 col-md-6 col-xl-7 align-middle">
                                <h4 class="card-title ">Pagos realizado por Clientes</h4>
                                <a style="color: #99ffff; font-size: 12px" href="{{ route('sales.create') }}">
                                    Crear Factura </a>
                            </div>
                            <div class="col-sm-8 col-md-6 col-xl-5 ">
                                <form action="{{ route('paymentclients.filter') }}" method="post" class="d-inline"
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
                                <a href="{{ route('paymentclients.create') }}">
                                    <button class="btn btn-info"> Generar Pago de Cliente
                                        <i class="material-icons" aria-hidden="true">person_add</i>
                                    </button> </a>
                            </div>
                        </div>
                        @if (session('message_status'))
                            @include('shared.message-session')
                        @endif
                    </div>
                    <form class="mt-3" method="POST" action="{{ route('paymentclients.filter') }}"> @csrf
                        @include('shared.filter')
                    </form>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
                                <thead class=" text-primary">
                                    <th>Item</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Forma de Pago</th>
                                    <th>Status</th>
                                    <th> </th>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr
                                            class="
                                            @switch($payment->status)
                                                        @case('Anulado')
                                                            bg-danger
                                                            @break
                                                        @case('Historico')
                                                            bg-warning
                                                            @break
                                                        @default
                                                            ''
                                                    @endswitch">
                                            <td> {{ $loop->iteration }} </td>
                                            <td> {{ $payment->Client->names }} </td>
                                            <td> {{ date('d-m-Y', strtotime($payment->payment_date)) }} </td>
                                            <td> {{ $payment->mount }} ({{ $payment->Coin->symbol }})
                                            </td>
                                            <td> {{ $payment->PaymentForm->description }} </td>
                                            <td> {{ $payment->status }} </td>
                                            <td> <a href="{{ route('paymentclients.show', $payment->id) }}">
                                                    <button class="btn-info" data-bs-toggle="tooltip" title="Ver Pago">
                                                        <i class="fa fa-table" aria-hidden="true"></i> </button>
                                                </a>
                                                <input type="hidden" id="message-item-delete" value=" Anular el Pago ">
                                                @if ($payment->status == 'Procesado')
                                                    <form action="{{ route('paymentclients.destroy', $payment->id) }}"
                                                        method="post" class="d-inline delete-item">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn-danger" data-bs-toggle="tooltip"
                                                            title="Anular Pago">
                                                            <i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('clients.balance', $payment->client_id) }}">
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
@endsection

@push('js')
    {{-- <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script> --}}
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js') }}/globalvars.js"></script>
@endpush
