<div class="row">
    <input type="hidden" value ="{{old('id',(isset($paymentform)?$paymentform->id:0))}}" name = "id">
    <input type="hidden" value ="{{isset($paymentform) ? old('user_id',$paymentform->user_id) : auth()->user()->id }}" name = "user_id">

    <label class="col-sm-3 col-form-label">{{ __('Forma de Pago') }}</label>
    <div class="col-sm-8">
      <div class="form-group{{ $errors->has('payment_form') ? ' has-danger' : '' }}">
        <input class="form-control{{ $errors->has('payment_form') ? ' is-invalid' : '' }}" name="payment_form" id="input-payment_form" type="text" 
        placeholder="{{ __('Forma de Pago') }}" value="{{ old('payment_form',(isset($paymentform) ? $paymentform->payment_form : '')) }}" required="true" aria-required="true"/>
        @if ($errors->has('payment_form'))
          <span id="paymentform-error" class="error text-danger" for="input-payment_form">{{ $errors->first('payment_form') }}</span>
        @endif
      </div>
    </div>
    <label class="col-sm-3 col-form-label">{{ __('Descripcion') }}</label>
    <div class="col-sm-8">
      <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
        <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="input-description" type="text"
        placeholder="{{ __('Descripción') }}" value="{{ old('description',(isset($paymentform) ? $paymentform->description : '')) }}" required="true" aria-required="true"/>
        @if ($errors->has('description'))
          <span id="description-error" class="error text-danger" for="input-description">{{ $errors->first('description') }}</span>
        @endif
      </div>
    </div>
</div>


