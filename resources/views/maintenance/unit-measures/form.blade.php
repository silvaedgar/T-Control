<div class="row">
    <input type="hidden" value="{{ old('id', isset($unitmeasure) ? $unitmeasure->id : 0) }}" name="id">
    <input type="hidden" value="{{ isset($unitmeasure) ? old('user_id', $unitmeasure->user_id) : auth()->user()->id }}"
        name="user_id">

    <label class="col-sm-3 mt-3">{{ __('Descripción: ') }}</label>
    <div class="col-sm-8" style="margin-left: -9rem">
        <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description"
                id="input-description" type="text" placeholder="{{ __('Descripción de Unidad') }}"
                value="{{ old('description', isset($unitmeasure) ? $unitmeasure->description : '') }}" required="true"
                aria-required="true" />
            <x-message-error :hasError="$errors->has('description')" :message="$errors->first('description')"></x-message-error>
        </div>
    </div>
    <label class="col-sm-3 mt-3">{{ __('Simbolo: ') }}</label>
    <div class="col-sm-8" style="margin-left: -9rem">
        <div class="form-group{{ $errors->has('symbol') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('symbol') ? ' is-invalid' : '' }}" name="symbol"
                id="input-symbol" type="text" placeholder="{{ __('Simbolo de Unidad de Medida') }}"
                value="{{ old('symbol', isset($unitmeasure) ? $unitmeasure->symbol : '') }}" required="true"
                aria-required="true" />
            <x-message-error :hasError="$errors->has('symbol')" :message="$errors->first('symbol')"></x-message-error>

        </div>
    </div>
</div>
