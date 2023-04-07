<div class="row">
    <input type="hidden" value="{{ old('id', isset($tax) ? $tax->id : 0) }}" name="id">
    <input type="hidden" value="{{ isset($tax) ? old('user_id', $tax->user_id) : auth()->user()->id }}" name="user_id">

    <div class="row">
        <label class="col-sm-3 mt-3">{{ __('Porcentaje de Impuesto: ') }}</label>
        <div class="col-sm-8">
            <div class="form-group{{ $errors->has('percent') ? ' has-danger' : '' }}">
                <input class="form-control{{ $errors->has('percent') ? ' is-invalid' : '' }}" name="percent"
                    id="input-percent" type="number" required aria-required="true" step="any"
                    placeholder="{{ __('Porcentaje de Impuesto') }}"
                    value="{{ old('percent', isset($tax) ? $tax->percent : '') }}">
                <x-message-error :hasError="$errors->has('percent')" :message="$errors->first('percent')"></x-message-error>
            </div>
        </div>
    </div>
    <div class="row">
        <label class="col-sm-3 mt-3">{{ __('Descripcion: ') }}</label>
        <div class="col-sm-8">
            <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description"
                    id="input-description" type="text" placeholder="{{ __('Descripcion') }}"
                    value="{{ old('description', isset($tax) ? $tax->description : '') }}" required="true"
                    aria-required="true" />
                <x-message-error :hasError="$errors->has('description')" :message="$errors->first('description')"></x-message-error>
            </div>
        </div>

    </div>
</div>
