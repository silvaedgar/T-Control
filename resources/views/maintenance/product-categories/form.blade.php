<div class="row">
    <label class="col-sm-2 mt-3">{{ __('Grupo: ') }}</label>
    <div class="col-sm-8">
        <div class="form-category{{ $errors->has('group_id') ? ' has-danger' : '' }}">
            <select class="form-control" name="group_id" id="group_id">
                <option value=0> Seleccione un Grupo ... </option>
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}> {{ $group->description }}
                    </option>
                @endforeach
            </select>
            <x-message-error :hasError="$errors->has('group_id')" :message="$errors->first('group_id')"></x-message-error>
        </div>
    </div>
</div>
<div class="row">
    <label class="col-sm-2 mt-3">{{ __('Descripcion: ') }}</label>
    <div class="col-sm-8">
        <div class="form-category{{ $errors->has('description') ? ' has-danger' : '' }}">
            <input aria-required="true" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" id="input-description"
                name="description" placeholder="{{ __('Descripción') }}" required="true" type="text"
                value="{{ old('description', isset($productcategory) ? $productcategory->description : '') }}" />
            <x-message-error :hasError="$errors->has('description')" :message="$errors->first('description')"></x-message-error>
        </div>
    </div>
</div>
