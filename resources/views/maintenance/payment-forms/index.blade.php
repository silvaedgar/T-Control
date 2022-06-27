@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __('Modulo Formas de Pago')])

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
                <div class="col-sm-6 col-md-8 flex-column ">
                    <h4 class="card-title ">Listado Formas de Pago</h4>
                </div>
                <div class="col-sm-4 float-end ">
                    <a href="{{route('maintenance.paymentforms.create')}}">
                        <button class="btn btn-info float-end"> Crear Forma de Pago
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
                    <th>Forma de Pago</th>
                    <th>Descripcion</th>
                    <th>Acción</th>
                </thead>
                <tbody>
                    @foreach ($paymentforms as $paymentform)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $paymentform->payment_form }} </td>
                        <td> {{ $paymentform->description }} </td>
                        <td>
                            <a href="{{route('maintenance.paymentforms.edit',$paymentform->id)}}">
                                <button class="btn-info" data-bs-toggle="tooltip" title="Editar Forma de Pago">
                                <i class="fa fa-edit"></i> </button> </a>
                            <input type="hidden" id="message-item-delete" value = " La Forma de Pago: {{ $paymentform->description}}">
                            <form action="{{ route('maintenance.paymentforms.destroy',$paymentform->id)}}" method="post"
                                class = "d-inline delete-item">
                                @csrf
                                @method('delete')
                                <button class="btn-danger"  data-bs-toggle="tooltip" title="Eliminar Forma de Pago">
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
@endsection

@push('js')
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js')}}/globalvars.js"> </script>
@endpush
