<div class="row">
    <label class="col-sm-2 col-form-label mt-3 ms-md-3 text-end ">{{ __('RIF/CI: ') }}</label>
    <div class="col-sm-8 form-group{{ $errors->has('document_type') ? ' has-danger' : '' }}">
        <div class="row">
            {!! Form::select(
                'document_type',
                ['J' => 'J', 'G' => 'G', 'V' => 'V', 'E' => 'E', 'P' => 'P'],
                isset($client) ? $client->document_type : 'V',
                ['class' => 'form-control me-3 ps-3 pt-3', 'style' => 'width:40px'],
            ) !!}
            <input class="form-control  {{ $errors->has('document') ? ' is-invalid' : '' }}" name="document"
                id="input-document" type="text" placeholder="{{ __('document') }}"
                value="{{ old('document', isset($client) ? $client->document : '') }}" required aria-required="false"
                style="width: 60%; margin-top: 5px">
            @if ($errors->has('document'))
                <div class="col-sm-12">
                    <span id="document-error" class="error text-danger"
                        for="input-document">{{ $errors->first('document') }}</span>
                </div>
            @endif
        </div>
    </div>
    <label class="col-sm-2 col-form-label mt-3 text-end">{{ __('Nombre Cliente: ') }}</label>
    <div class="col-sm-9 form-group{{ $errors->has('names') ? ' has-danger' : '' }}">
        <input class="ms-1 form-control{{ $errors->has('names') ? ' is-invalid' : '' }}" style="width: 400px" name="names"
            id="input-names" type="text" placeholder="{{ __('Nombre Cliente') }}"
            value="{{ old('names', isset($client) ? $client->names : '') }}" required />
        @if ($errors->has('names'))
            <span id="names-error" class="error text-danger" for="input-names">{{ $errors->first('names') }}</span>
        @endif
    </div>
    <label class="col-sm-2 col-form-label mt-3 text-end">{{ __('Dirección:') }}</label>
    <div class="col-sm-8">
        <div class="form-group{{ $errors->has('symbol') ? ' has-danger' : '' }}">
            <input class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" name="address"
                id="input-address" type="text" placeholder="{{ __('address') }}"
                value="{{ old('address', isset($client) ? $client->address : '') }}" required aria-required="false" />
            @if ($errors->has('address'))
                <span id="address-error" class="error text-danger"
                    for="input-address">{{ $errors->first('address') }}</span>
            @endif
        </div>
    </div>
</div>
