<div class="row">
    <input type="hidden" value ="{{old('id',(isset($unitmeasure)?$unitmeasure->id:0))}}" name = "id">
    <input type="hidden" value ="{{isset($unitmeasure) ? old('user_id',$unitmeasure->user_id) : auth()->user()->id }}" name = "user_id">

    <label class="col-sm-3 col-form-label">{{ __('Descripción') }}</label>
    <div class="col-sm-8">
      <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
        <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="input-description" type="text"
        placeholder="{{ __('Descripción de Unidad') }}" value="{{ old('description',(isset($unitmeasure) ? $unitmeasure->description : '')) }}" required="true" aria-required="true"/>
        @if ($errors->has('description'))
          <span id="unitmeasure-error" class="error text-danger" for="input-description">{{ $errors->first('description') }}</span>
        @endif
      </div>
    </div>
    <label class="col-sm-3 col-form-label">{{ __('Simbolo') }}</label>
    <div class="col-sm-8">
      <div class="form-group{{ $errors->has('symbol') ? ' has-danger' : '' }}">
        <input class="form-control{{ $errors->has('symbol') ? ' is-invalid' : '' }}" name="symbol" id="input-symbol" type="text"
        placeholder="{{ __('Simbolo de Unidad de Medida') }}" value="{{ old('symbol',(isset($unitmeasure) ? $unitmeasure->symbol : '')) }}" required="true" aria-required="true"/>
        @if ($errors->has('symbol'))
          <span id="symbol-error" class="error text-danger" for="input-symbol">{{ $errors->first('symbol') }}</span>
        @endif
      </div>
    </div>
</div>


