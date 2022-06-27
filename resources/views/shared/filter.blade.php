<div class="container mx-auto" style="width: 80%">
    <input type="hidden" value="Query" name="option">
    <div class="row">
        <div class="col-sm-4 col-md-3">
            Status:
            @if ($data_common['controller'] == 'PaymentClient' || $data_common['controller'] == 'PaymentSupplier')
                <select name='status'>
                    <option value='Select'> Seleccione un Status ... </option>
                    <option value='Todos' {{ $data_common['data_filter']['status'] == 'Todos' ? 'selected' : '' }}>
                        Todos </option>
                    <option value='Procesado'
                        {{ $data_common['data_filter']['status'] == 'Procesado' ? 'selected' : '' }}>
                        Procesados
                    </option>
                    <option value='Anulado'
                        {{ $data_common['data_filter']['status'] == 'Anulado' ? 'selected' : '' }}>
                        Anulados
                    </option>
                    <option value='Historico'
                        {{ $data_common['data_filter']['status'] == 'Historico' ? 'selected' : '' }}>
                        Historicos
                    </option>
                </select>
            @else
                <select name='status'>
                    <option value='Select'> Seleccione un Status ... </option>
                    <option value='Todos' {{ $data_common['data_filter']['status'] == 'Todos' ? 'selected' : '' }}>
                        Todos </option>
                    <option value='Pendiente'
                        {{ $data_common['data_filter']['status'] == 'Pendiente' ? 'selected' : '' }}>
                        Pendientes </option>
                    <option value='Cancelada'
                        {{ $data_common['data_filter']['status'] == 'Cancelada' ? 'selected' : '' }}>
                        Canceladas </option>
                    <option value='Anulada'
                        {{ $data_common['data_filter']['status'] == 'Anulada' ? 'selected' : '' }}>
                        Anuladas
                    </option>
                    <option value='Historico'
                        {{ $data_common['data_filter']['status'] == 'Historico' ? 'selected' : '' }}>
                        Historicas </option>
                </select>
            @endif
        </div>
        <div class="col-sm-3 col-md-3 ">
            Inicio:<input type="date" name="startdate" value="{{ $data_common['data_filter']['date_start'] }}">
        </div>
        <div class="col-sm-3 col-md-3">
            Fin: <input type="date" name="enddate" value="{{ $data_common['data_filter']['date_end'] }}">
        </div>
        <div class="col-sm-2 col-md-2">
            <button class="btn-success" type="submit"> Consultar </button>
        </div>
    </div>
</div>
