<div class="row">
    <label class="col-sm-2 mt-3">{{ __('Forma de Pago') }}</label>
    <div class="col-sm-8">
        <div class="form-group{{ $errors->has('payment_form') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('payment_form') ? ' is-invalid' : '' }}" name="payment_form" id="input-payment_form" type="text"
                placeholder="{{ __('Forma de Pago') }}" value="{{ old('payment_form', isset($paymentform) ? $paymentform->payment_form : '') }}"
                required="true" aria-required="true" />
            <x-message-error :hasError="$errors->has('payment_form')" :message="$errors->first('payment_form')"></x-message-error>
        </div>
    </div>

</div>
<div class="row">
    <label class="col-sm-2 mt-3">{{ __('Descripcion: ') }}</label>
    <div class="col-sm-8">
        <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
            <input class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" id="input-description" type="text"
                placeholder="{{ __('Descripción') }}" value="{{ old('description', isset($paymentform) ? $paymentform->description : '') }}"
                required="true" aria-required="true" />
            <x-message-error :hasError="$errors->has('description')" :message="$errors->first('description')"></x-message-error>
        </div>
    </div>

</div>
