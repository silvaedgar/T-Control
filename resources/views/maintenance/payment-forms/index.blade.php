@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __('Modulo Formas de Pago')])

@section('css')
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
                    <h4 class="card-title ">Listado Formas de Pago</h4>
                </div>
                <div class="col-3 justify-end">
                    <a href="{{route('maintenance.paymentforms.create')}}">
                        <button class="btn btn-info"> Crear Forma de Pago
                            <i class="material-icons" aria-hidden="true">person_add</i>
                        </button> </a>
                </div>
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped" id="paymentforms" style="width: 100%">
                <thead class=" text-primary">
                    <th>Item</th>
                    <th>Forma de Pago</th>
                    <th>Descripcion</th>
                    <th>Acción</th>
                </thead>
                <tbody>
                    @foreach ($paymentforms as $paymentform)
                    <tr style="height: 1%">
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $paymentform->payment_form }} </td>
                        <td> {{ $paymentform->description }} </td>

                        <td>
                            <div class="row">
                                <div class="col-2">
                                    <a href="{{route('maintenance.paymentforms.edit',$paymentform->id)}}">
                                        <button class="btn-sm btn-danger" data-bs-toggle="tooltip" title="Editar Forma de Pago">
                                            <i class="fa fa-edit"></i> </button> </a>
                                </div>
                                <div class="col-2">
                                    <form action="{{ route('maintenance.paymentforms.destroy',$paymentform->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                           <button class="btn-sm btn-danger"  data-bs-toggle="tooltip" title="Eliminar Forma de Pago"
                                                    onclick = "return DeleteRecord('Seguro que desea eliminar la Forma de Pago?')">
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
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset ('js') }}/functions.js"> </script>
    <script>
        $(document).ready(function() {
            $('#paymentforms').DataTable({
                lengthMenu : [[5,10,15,-1],[5,10,20,"All"]],
                responsive : true,
                autoWidth : false
            });
        });
     </script>
@endpush
