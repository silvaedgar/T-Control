<div class="row p-0 align-items-center">
    <div class="col-3 col-md-4 text-right px-1 align-items-center">
        <span class="form-label "> {{ $config['var_header']['labelCaption'] }}</span>
    </div>
    <div class="col px-1 ml-0">
        @if (isset($invoice))
            <input type="text" value="{{ $config['var_header']['name'] }}" class="form-control " />
        @else
            @php
                $variable = $config['var_header']['id'];
            @endphp
            <select name="{{ $config['var_header']['id'] }}" id="{{ $config['var_header']['id'] }}" class="select2 w-100"
                {{ isset($invoice) ? '' : '' }} onchange="SearchIdPayment('{{ $config['var_header']['id'] }}')">
                <option value=0> Seleccione un {{ $config['var_header']['labelCaption'] }} ... </option>
                @foreach ($config['var_header']['table'] as $table)
                    <option value="{{ $table->id }}"
                        {{ old($config['var_header']['id']) == $table->id || (isset($invoice) && $invoice->$variable == $table->id) ? 'selected' : '' }}>
                        {{ isset($table->names) ? $table->names : $table->name }}
                    </option>
                @endforeach
            </select>
        @endif
    </div>
    <x-message-error :hasError="$errors->has($config['var_header']['id'])" :message="$errors->first($config['var_header']['id'])"></x-message-error>
</div>
