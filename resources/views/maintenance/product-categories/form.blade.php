<div class="row">
    <input type="hidden" value="{{ old('id', isset($productcategory) ? $productcategory->id : 0) }}" name="id">
    <input type="hidden"
        value="{{ isset($productcategory) ? old('user_id', $productcategory->user_id) : auth()->user()->id }}"
        name="user_id">
    <label class="col-sm-3 col-form-label">{{ __('Grupo') }}</label>
    <div class="col-sm-8">
        <div class="form-category{{ $errors->has('group_id') ? ' has-danger' : '' }}">
            <select name="group_id" class="form-control">
                <option value=0> Seleccione un Grupo ... </option>
                @foreach ($groups as $group)
                    <option
                        @if (isset($productcategory)) @if ($group->id == $productcategory->group_id)
                            selected @endif
                        @endif
                        value="{{ $group->id }}"> {{ $group->description }} </option>
                @endforeach
            </select>
            @if ($errors->has('group_id'))
                <span id="group_id-error" class="error text-danger"
                    for="input-group_id">{{ $errors->first('group_id') }}</span>
            @endif
        </div>
    </div>
    <label class="col-sm-3 col-form-label">{{ __('Descripcion') }}</label>
    <div class="col-sm-8">
        <div class="form-category{{ $errors->has('description') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description"
                id="input-description" type="text" placeholder="{{ __('Descripción') }}"
                value="{{ old('description', isset($productcategory) ? $productcategory->description : '') }}"
                required="true" aria-required="true" />
            @if ($errors->has('description'))
                <span id="description-error" class="error text-danger"
                    for="input-description">{{ $errors->first('description') }}</span>
            @endif
        </div>
    </div>
</div>
