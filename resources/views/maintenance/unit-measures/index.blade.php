@extends('layouts.app', ['activePage' => 'maintenance', 'titlePage' => __('Modulo de Unidad de Medida')])

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
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
                    <h4 class="card-title ">Unidades de Medida</h4>
                </div>
                <div class="col-3 justify-end">
                    <a href="{{route('maintenance.unitmeasures.create')}}">
                        <button class="btn btn-info"> Crear Unidad
                            <i class="material-icons" aria-hidden="true">person_add</i>
                        </button> </a>
                </div>
              </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table-sm table-hover table-striped" id="data-table" style="width: 100%">
                <thead class=" text-primary">
                    <th></th>
                    <th>Item</th>
                    <th>Nombre</th>
                    <th>Simbolo</th>
                </thead>
                <tbody>
                    @foreach ($unitmeasures as $unitmeasure)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $unitmeasure->description }} </td>
                        <td> {{ $unitmeasure->symbol }} </td>
                        <td>
                            <a href="{{route('maintenance.unitmeasures.edit',$unitmeasure->id)}}">
                                <button class="btn-sm btn-danger" data-bs-toggle="tooltip" title="Editar Unidad de Medida">
                                <i class="fa fa-edit"></i> </button> </a>
                            <input type="hidden" id="message-item-delete" value = " la Unidad de Medida: {{ $unitmeasure->description}}">
                            <form action="{{ route('maintenance.unitmeasures.destroy',$unitmeasure->id)}}"  method="post"
                                    class = "d-inline" id="delete-item">
                                @csrf
                                @method('delete')
                                <button class="btn-sm btn-danger"  data-bs-toggle="tooltip" title="Eliminar Unidad de Medida">
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
    <script src="{{asset('js')}}\globalvars.js"> </script>
@endpush
