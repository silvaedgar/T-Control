@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __('Modulo de Monedas')])

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
                    <h4 class="card-title ">Listado de Monedas</h4>
                </div>
                <div class="col-3 justify-end">
                    <a href="{{route('maintenance.coins.create')}}">
                        <button class="btn btn-info"> Crear Moneda
                            <i class="material-icons" aria-hidden="true">person_add</i>
                        </button> </a>
                </div>
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped" id="coins" style="width: 100%">
                <thead class=" text-primary">
                    <th> </th>
                    <th>Item</th>
                    <th>Nombre</th>
                    <th>Simbolo</th>
                    <th> Acciones </th>
                </thead>
                <tbody>
                    @foreach ($coins as $coin)
                    <tr>
                        <td style="font-size:10px">
                            @if ($coin->base_currency=='S')
                                MB
                            @endif
                            @if ($coin->calc_currency_purchase == 'S')
                            MCC
                            @endif
                            @if ($coin->calc_currency_sale == 'S')
                            MCV
                            @endif

                        </td>
                        <td>   {{ $loop->iteration }} </td>
                        <td> {{ $coin->name }} </td>
                        <td> {{ $coin->symbol }} </td>
                        <td>
                            <div class="row">
                                <div class="col-2">
                                    <a href="{{route('maintenance.coins.edit',$coin->id)}}">
                                        <button class="btn-sm btn-danger" data-bs-toggle="tooltip" title="Editar Moneda">
                                            <i class="fa fa-edit"></i> </button> </a>
                                </div>
                                <div class="col-2">
                                    <form action="{{ route('maintenance.coins.destroy',$coin->id)}}" method="post">
                                        @csrf
                                        @method('delete')
                                           <button class="btn-sm btn-danger"  data-bs-toggle="tooltip" title="Eliminar Moneda"
                                                    onclick = "return DeleteRecord('Seguro que desea eliminar la moneda?')">
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
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js') }}/functions.js"> </script>

    <script>
        $(document).ready(function() {
            $('#coins').DataTable({
                lengthMenu : [[5,10,15,-1],[5,10,20,"All"]],
                responsive : true,
                autoWidth : false
            });
        });

    function EliminarRegistro(value){

        alert("entre a eliminar registro")
        // confirm(value) ? true: event.preventDefault()

        // confirm(value) ? true: event.preventDefault()
            // event.preventDefault();
            // Swal.fire({
            //     title: 'Esta Seguro?',
            //     text: value,
            //     icon: 'question',
            //     showCancelButton: true,
            //     confirmButtonColor: '#3085d6',
            //     cancelButtonColor: '#d33',
            //     confirmButtonText: 'Si'
            // }).then((result) => {

            //     if (result.isConfirmed) {
            //         $(this).trigger('click');
            //     }
            // })

            // this.closest('form').submit()
    }

    </script>
@endpush
