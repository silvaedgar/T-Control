<div class="row">
    <input type="hidden" value ="{{isset($client) ? old('id',$client->id) : 0}}" name = "id">
    <input type="hidden" value ="{{isset($client) ? old('user_id',$client->user_id) : auth()->user()->id }}" name = "user_id">
    <label class="col-sm-3 col-form-label">{{ __('RIF/CI') }}</label>
    <div class="col-sm-8 form-group{{ $errors->has('document_type') ? ' has-danger' : '' }}">
        <div class="row">
            {!! Form::select('document_type',['J'=>'J','G'=>'G','V'=>'V','E'=>'E','P'=>'P'],
                isset($client) ? $client->document_type : 'V', ['class'=>'form-control',
                'style' => 'width:40px']) !!}
            &nbsp; &nbsp;    
            <input class="form-control {{ $errors->has('document') ? ' is-invalid' : '' }}" 
                name="document" id="input-document" type="text" placeholder="{{ __('document') }}"
                value="{{ old('document', (isset($client) ? $client->document :''))}}" 
                required aria-required="false" style="width: 60%">
            @if ($errors->has('document'))
                <span id="document-error" class="error text-danger" for="input-document">{{ $errors->first('document') }}</span>
            @endif
        </div>
    </div>
    <label class="col-sm-3 col-form-label">{{ __('Nombre Cliente') }}</label>
    <div class="col-sm-8 form-group{{ $errors->has('names') ? ' has-danger' : '' }}">
        <input class="form-control{{ $errors->has('names') ? ' is-invalid' : '' }}" style="width: 400px"
            name="names" id="input-names" type="text" placeholder="{{ __('Nombre Cliente') }}"
            value="{{ old('names',(isset($client)? $client->names:'')) }}" required />
        @if ($errors->has('names'))
          <span id="names-error" class="error text-danger" for="input-names">{{ $errors->first('names') }}</span>
        @endif
    </div>
    <label class="col-sm-3 col-form-label">{{ __('Dirección') }}</label>
    <div class="col-sm-8">
        <div class="form-group{{ $errors->has('symbol') ? ' has-danger' : '' }}">
            <input class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}"
            name="address" id="input-address" type="text" placeholder="{{ __('address') }}"
            value="{{ old('address', (isset($client) ? $client->address :'')) }}" required aria-required="false"/>
            @if ($errors->has('address'))
                <span id="address-error" class="error text-danger" for="input-address">{{ $errors->first('address') }}</span>
            @endif
        </div>
    </div>
</div>  