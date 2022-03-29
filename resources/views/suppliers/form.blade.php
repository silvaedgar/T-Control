<div class="row">
    <input type="hidden" value ="{{isset($supplier) ? old('id',$supplier->id) : 0}}" name = "id">
    <input type="hidden" value ="{{isset($supplier) ? old('user_id',$supplier->user_id) : auth()->user()->id }}" name = "user_id">


    <label class="col-sm-2 col-form-label">{{ __('RIF/CI') }}</label>
    <div>
        <div class="row">
            <div class="col-1 text-right">
                {!! Form::select('document_type',['J'=>'J','G'=>'G','V'=>'V','E'=>'E','P'=>'P'],
                isset($supplier) ? $supplier->document_type : 'J', ['class'=>'form-control','style' => 'width:40px']) !!}
            </div>
            <div class="col-4 text-left">
                <input class="form-control {{ $errors->has('document') ? ' is-invalid' : '' }}" style ="width:75%"
                name="document" id="input-document" type="text" placeholder="{{ __('document') }}"
                value="{{ old('document', (isset($supplier) ? $supplier->document :''))}}" required aria-required="false">
                @if ($errors->has('document'))
                    <span id="document-error" class="error text-danger" for="input-document">{{ $errors->first('document') }}</span>
                @endif
            </div>
            <div class="col-1">
                <label > Nombre </label>
            </div>
            <div class="col-6">
                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" style="width: 400px"
                        name="name" id="input-name" type="name" placeholder="{{ __('name') }}"
                        value="{{ old('name',(isset($supplier)? $supplier->name:'')) }}" required />
                    @if ($errors->has('name'))
                      <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                    @endif
                </div>

            </div>
            </div>
            {{-- <div class="form-group{{ $errors->has('document_type') ? ' has-danger' : '' }}">
            </div> --}}
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-1">
                <label>Contacto</label>
            </div>
            <div class="col-3">
                <input class="form-control {{ $errors->has('contact') ? ' is-invalid' : '' }}"
                name="contact" id="input-contact" type="text" placeholder="{{ __('Contacto') }}"
                value="{{ old('contact', (isset($supplier) ? $supplier->contact : '')) }}" required aria-required="false"/>
                @if ($errors->has('contact'))
                    <span id="contact-error" class="error text-danger" for="input-contact">{{ $errors->first('contact') }}</span>
                @endif
            </div>
            <div class="col-1">
                <label>{{ __('Direccion') }}</label>
            </div>
            <div class="col-3">
                <input class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}"
                name="address" id="input-address" type="text" placeholder="{{ __('address') }}"
                value="{{ old('address', (isset($supplier) ? $supplier->address :'')) }}" required aria-required="false"/>
                @if ($errors->has('address'))
                    <span id="address-error" class="error text-danger" for="input-address">{{ $errors->first('address') }}</span>
                @endif
            </div>
            <div class="col-1">
                <label>{{ __('Telefono') }}</label>
            </div>
            <div class="col-3">
                <div class="form-group{{ $errors->has('phone') ? ' has-danger' : '' }}">
                    <input class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                        name="phone" id="input-phone" type="phone" placeholder="{{ __('phone') }}"
                        value="{{ old('phone',(isset($supplier)? $supplier->phone:'')) }}" required />
                    @if ($errors->has('phone'))
                      <span id="phone-error" class="error text-danger" for="input-phone">{{ $errors->first('phone') }}</span>
                    @endif
                </div>
            </div>
            </div>
    </div>
</div>

