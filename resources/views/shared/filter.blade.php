<div class="container mx-auto" style="width: 80%">
    <input type="hidden" value="Query" name="option">
    <div class="row">
        <div class="col-sm-4">
            Status:
            <select name='status'>
                <option value='Select'> Seleccione un Status ... </option>
                @foreach ($config['data']['status'] as $status)
                    <option value='{{ $status }}' {{ $config['data']['dataFilter']['status'] == $status ? 'selected' : '' }}>
                        {{ $status }}s</option>
                @endforeach
            </select>
            {{-- @if ($data_common['controller'] == 'PaymentClient' || $data_common['controller'] == 'PaymentSupplier')
                <select name='status'>
                    <option value='Select'> Seleccione un Status ... </option>
                    <option value='Todos' {{ $data_common['dataFilter']['status'] == 'Todos' ? 'selected' : '' }}>
                        Todos </option>
                    <option value='Procesado' {{ $data_common['dataFilter']['status'] == 'Procesado' ? 'selected' : '' }}>
                        Procesados
                    </option>
                    <option value='Anulado' {{ $data_common['dataFilter']['status'] == 'Anulado' ? 'selected' : '' }}>
                        Anulados
                    </option>
                    <option value='Historico' {{ $data_common['dataFilter']['status'] == 'Historico' ? 'selected' : '' }}>
                        Historicos
                    </option>
                </select>
            @else
                <select name='status'>
                    <option value='Select'> Seleccione un Status ... </option>
                    <option value='Todos' {{ $data_common['dataFilter']['status'] == 'Todos' ? 'selected' : '' }}>
                        Todos </option>
                    <option value='Pendiente' {{ $data_common['dataFilter']['status'] == 'Pendiente' ? 'selected' : '' }}>
                        Pendientes </option>
                    <option value='Cancelada' {{ $data_common['dataFilter']['status'] == 'Cancelada' ? 'selected' : '' }}>
                        Canceladas </option>
                    <option value='Anulada' {{ $data_common['dataFilter']['status'] == 'Anulada' ? 'selected' : '' }}>
                        Anuladas
                    </option>
                    <option value='Historico' {{ $data_common['dataFilter']['status'] == 'Historico' ? 'selected' : '' }}>
                        Historicas </option>
                </select>
            @endif --}}
        </div>
        <div class="col-sm-3 col-md-3" style="margin-left:-50px">
            Inicio:<input type="date" name="startdate" value="{{ $config['data']['dataFilter']['date_start'] }}">
        </div>
        <div class="col-sm-3 col-md-3">
            Fin: <input type="date" name="enddate" value="{{ $config['data']['dataFilter']['date_end'] }}">
        </div>
        <div class="col-sm-2 col-md-2">
            <button class="btn-success" type="submit"> Consultar </button>
        </div>
    </div>
</div>
