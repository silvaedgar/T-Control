<div class="container mx-auto" style="width: 80%">
    <div class="row">
        <div class="col-sm-4 col-md-3">
            Status:
            @if (isset($payment))
                <select name='status'>
                    <option value = 'Select'> Seleccione un Status ... </option>
                    <option value='Todos' {{ ($status == 'Todos' ? 'selected' : '') }}> Todos </option>
                    <option value='Procesado' {{ ($status == 'Procesado' ? 'selected' : '') }}> Procesados </option>
                    <option value='Anulado' {{ ($status == 'Anulado' ? 'selected' : '') }}> Anulados </option>
                </select>
            @else
                <select name='status'>
                    <option value = 'Select'> Seleccione un Status ... </option>
                    <option value='Todos' {{ ($status == 'Todos' ? 'selected' : '') }}> Todos </option>
                    <option value='Pendiente' {{ ($status == 'Pendiente' ? 'selected' : '') }}> Pendientes </option>
                    <option value='Parcial' {{ ($status == 'Parcial' ? 'selected' : '') }}> Parcialmente Canceladas </option>
                    <option value='Cancelada' {{ ($status == 'Cancelada' ? 'selected' : '') }}> Canceladas </option>
                </select>
            @endif
        </div>
        <div class="col-sm-3 col-md-3 ">
            Inicio:<input type = "date" name="startdate" value = "{{ $datestart}}">
        </div>
        <div class="col-sm-3 col-md-3">
            Fin: <input type = "date" name="enddate" value = "{{$dateend}}" >
        </div>
        <div class="col-sm-2 col-md-2">
            <button class="btn-success" type="submit"> Consultar  </button>
        </div>
    </div>
</div>
