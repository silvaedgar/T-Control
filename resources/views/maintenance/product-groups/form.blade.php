<div class="row">
    <label class="col-sm-2 mt-3">{{ __('Descripcion: ') }}</label>
    <div class="col-sm-7" style="margin-left: -3.5rem">
        {{-- <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}"> --}}
        <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description"
            id="input-description" type="text" placeholder="{{ __('Descripción') }}"
            value="{{ old('description', isset($productgroup) ? $productgroup->description : '') }}" required="true"
            aria-required="true" />
        <x-message-error :hasError="$errors->has('description')" :message="$errors->first('description')"></x-message-error>
        {{-- </div> --}}
    </div>
</div>
