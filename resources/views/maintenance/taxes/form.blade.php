<div class="row">
    <input type="hidden" value ="{{old('id',(isset($tax)?$tax->id:0))}}" name = "id">
    <input type="hidden" value ="{{isset($tax) ? old('user_id',$tax->user_id) : auth()->user()->id }}" name = "user_id">

    <label class="col-sm-3 col-form-label">{{ __('Porcentaje de Impuesto') }}</label>
    <div class="col-sm-8">
      <div class="form-group{{ $errors->has('percent') ? ' has-danger' : '' }}">
        <input class="form-control{{ $errors->has('percent') ? ' is-invalid' : '' }}" name="percent" 
            id="input-percent" type="number" required aria-required="true" step="any"
            placeholder="{{ __('Porcentaje de Impuesto') }}" value="{{ old('percent',(isset($tax) ? $tax->percent : '')) }}"> 
        @if ($errors->has('percent'))
          <span id="tax-error" class="error text-danger" for="input-percent">{{ $errors->first('percent') }}</span>
        @endif
      </div>
    </div>
    <label class="col-sm-3 col-form-label">{{ __('Descripcion') }}</label>
    <div class="col-sm-8">
      <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
        <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="input-description" type="text"
        placeholder="{{ __('Descripcion') }}" value="{{ old('description',(isset($tax) ? $tax->description : '')) }}" required="true" aria-required="true"/>
        @if ($errors->has('description'))
          <span id="description-error" class="error text-danger" for="input-description">{{ $errors->first('description') }}</span>
        @endif
      </div>
    </div>
</div>


