<div class="row">
    <input type="hidden" value ="{{old('id',(isset($productgroup)?$productgroup->id:0))}}" name = "id">
    <input type="hidden" value ="{{isset($productgroup) ? old('user_id',$productgroup->user_id) : auth()->user()->id }}" name = "user_id">

    <label class="col-sm-2 col-form-label">{{ __('Descripcion') }}</label>
    <div class="col-sm-7">
      <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
        <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="input-description" type="text"
        placeholder="{{ __('Descripción') }}" value="{{ old('description',(isset($productgroup) ? $productgroup->description : '')) }}" required="true" aria-required="true"/>
        @if ($errors->has('description'))
          <span id="description-error" class="error text-danger" for="input-description">{{ $errors->first('description') }}</span>
        @endif
      </div>
    </div>
</div>


