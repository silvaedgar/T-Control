<div class="row">
    <input type="hidden" value="{{ old('id', isset($coin) ? $coin->id : 0) }}" name="id">
    <input type="hidden" value="{{ isset($coin) ? old('user_id', $coin->user_id) : auth()->user()->id }}" name="user_id">

    <label class="col-sm-3 mt-3">{{ __('Nombre de la Moneda: ') }}</label>
    <div class="col-sm-8">
        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}" style="margin-left: -5rem">
            <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name"
                type="text" placeholder="{{ __('Nombre de Moneda') }}"
                value="{{ old('name', isset($coin) ? $coin->name : '') }}" required="true" aria-required="true" />
            <x-message-error :hasError="$errors->has('name')" :message="$errors->first('name')"></x-message-error>
        </div>
    </div>
    <label class="col-sm-2 mt-3">{{ __('Simbolo: ') }}</label>
    <div class="col-sm-8">
        <div class="form-group{{ $errors->has('symbol') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('symbol') ? ' is-invalid' : '' }}" name="symbol"
                id="input-symbol" type="text" placeholder="{{ __('Simbolo de Moneda') }}"
                value="{{ old('symbol', isset($coin) ? $coin->symbol : '') }}" required="true" aria-required="true" />
            <x-message-error :hasError="$errors->has('symbol')" :message="$errors->first('symbol')"></x-message-error>
        </div>
    </div>
</div>
