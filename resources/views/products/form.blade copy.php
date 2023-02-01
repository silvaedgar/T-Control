<input type="hidden" value="{{ isset($product) ? old('id', $product->id) : 0 }}" name="id">
<input type="hidden" value="{{ isset($product) ? old('user_id', $product->user_id) : auth()->user()->id }}"
    name="user_id">
<input type="hidden" value="{{ isset($product) ? old('category_id', $product->category_id) : 0 }}" id="category_id">
<input type="hidden" value="{{ $data_common['purchase_rate'] }}" id="rate_cost">
<input type="hidden" value="{{ $data_common['sale_rate'] }}" id="rate_sale">
<input type="hidden" value="{{ $data_common['base_coin_symbol'] }}" id="base_coin_symbol">
<input type="hidden" value="{{ $data_common['purchase_coin_symbol'] }}" id="purchase_coin_symbol">
<input type="hidden" value="{{ $data_common['sale_coin_symbol'] }}" id="sale_coin_symbol">

{{-- <div class="col-12 col-md-4 col-sm-1"> --}}
<div class="row mt-3">
    <div class="col-sm-10">
        <div class="row">
            <div class="col-sm-2 col-md-1">
                <label class="col-form-label">{{ __('Codigo') }}</label>
            </div>
            <div class="col-sm-9 col-md-3 ml-sm-0 " style="margin-top:-5px">
                <input class="form-control " name="code" id="input-code" type="text"
                    placeholder="{{ __('Codigo del Producto') }}"
                    value="{{ old('code', isset($product) ? $product->code : '') }}" required aria-required="false">
                @error('code')
                    <span class="text-danger"> {{ $message }} </span> <br />
                @enderror
            </div>
            <div class="col-sm-3 col-md-2">
                <label class="col-form-label"> {{ __('Descripción') }} </label>
            </div>
            <div class="col-sm-8 col-md-6" style="margin-top:-5px">
                <input class="form-control" name="name" id="input-name" type="text"
                    placeholder="{{ __('Ingrese nombre') }}"
                    value="{{ old('name', isset($product) ? $product->name : '') }}" required aria-required="false">
                @error('name')
                    <span class="text-danger"> {{ $message }} </span> <br />
                @enderror
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-4 col-md-2">
                <label class="col-form-label">{{ __('Grupo Producto') }}</label>
            </div>
            <div class="col-sm-8 col-md-2">
                <select name="group_id" id="group-id" class="form-control" onchange="LoadCategories()">
                    <option value=0> Seleccione un Grupo ... </option>
                    @foreach ($data['cateo'] as $group)
                        <option
                            @if (isset($group_id)) @if ($group_id[0]->id == $group->id)
                                    selected @endif
                            @endif
                            value = "{{ $group->id }}">
                            {{ $group->description }} </option>
                    @endforeach
                </select>
                @error('group_id')
                    <span class="text-danger"> {{ $message }} </span> <br />
                @enderror
            </div>
            <div class="col-sm-5 col-md-3">
                <label class="col-form-label">{{ __('Categoria Producto') }}</label>
            </div>
            <div class="col-sm-7 col-md-5 col-xl-4">
                <select name="category_id" id="category-id" class="form-control">
                    <option value=0> Seleccione la Categoria ... </option>
                </select>
                @error('category_id')
                    <span class="text-danger"> {{ $message }} </span> <br />
                @enderror
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 col-md-1">
                <label class="col-form-label">{{ __('Tipo Impuesto') }}</label>
            </div>
            <div class="col-sm-7 col-md-2">
                <select name="tax_id" id="tax_id" class="form-control">
                    <option value=0> Seleccione un tipo de impuesto ... </option>
                    @foreach ($taxes as $tax)
                        <option value="{{ $tax->id }}"
                            @if (isset($product)) @if ($product->tax_id == $tax->id)
                                        selected @endif
                            @endif
                            > {{ $tax->description }} </option>
                    @endforeach
                </select>
                @error('tax_id')
                    <span class="text-danger"> {{ $message }} </span> <br />
                @enderror
            </div>
            <div class="col-sm-2 col-md-2">
                <label class="col-form-label">{{ __('Precio Costo') }}
                    {{ $data_common['purchase_coin_symbol'] }}</label>
            </div>
            <div class="col-sm-4 col-md-2 ">
                <input class="form-control" name="cost_price" id="cost_price" type="number" step="any"
                    value="{{ old('cost_price', isset($product) ? $product->cost_price : '') }}" required
                    aria-required="false"
                    onkeyup=" document.getElementById('cost_price_other').value = parseFloat(document.getElementById('cost_price').value * document.getElementById('rate_cost').value).toFixed(2)   ">
                @if ($data_common['purchase_coin_symbol'] != $data_common['base_coin_symbol'])
                    <input class="form-control" name="cost_price_other" id="cost_price_other" type="number"
                        step="any"
                        value="{{ old('cost_price', isset($product) ? number_format($product->cost_price * $data_common['purchase_rate'], 2) : '') }}"
                        required aria-required="false"
                        onkeyup=" document.getElementById('cost_price').value = parseFloat(document.getElementById('cost_price_other').value / document.getElementById('rate_cost').value).toFixed(2)   ">
                @endif
                @error('cost_price')
                    <span class="text-danger"> {{ $message }} </span> <br />
                @enderror
            </div>
            <div class="col-sm-2">
                <label class="col-form-label">{{ __('Precio Venta') }} {{ $data_common['sale_coin_symbol'] }}
                </label>
            </div>
            <div class="col-sm-3">
                <input class="form-control {{ $errors->has('sale_price') ? ' is-invalid' : '' }}" name="sale_price"
                    id="sale_price" type="number" step="any"
                    value="{{ old('sale_price', isset($product) ? $product->sale_price : '') }}" required
                    aria-required="false"
                    onkeyup=" document.getElementById('sale_price_other').value = parseFloat(document.getElementById('sale_price').value * document.getElementById('rate_sale').value).toFixed(2)   ">
                @if ($data_common['sale_coin_symbol'] != $data_common['base_coin_symbol'])
                    <input class="form-control {{ $errors->has('sale_price') ? ' is-invalid' : '' }}"
                        name="sale_price_other" id="sale_price_other" type="number" step="any"
                        value="{{ old('sale_price', isset($product) ? number_format($product->sale_price * $data_common['sale_rate'], 2) : '') }}"
                        required aria-required="false"
                        onkeyup=" document.getElementById('sale_price').value = parseFloat(document.getElementById('sale_price_other').value / document.getElementById('rate_sale').value).toFixed(2)   ">
                @endif
                @error('sale_price')
                    <span class="text-danger"> {{ $message }} </span> <br />
                @enderror
            </div>
        </div>
    </div>
    {{-- {{ asset($product->image_file) }} --}}
    <div class="col-sm-2 border">
        <label for="imagefile"> <i class="fa fa-plus" aria-hidden="true"
                style="font-size:15px;  padding: 5px; cursor: pointer; margin-left:-15px; ">
            </i></label>
        {{-- <button class="btn-image" onclick="document.getElementById('imagefile').click()"> Leer Archivo</button> --}}
        {{-- El id y name de type file no permitio camelCase ni snake_case no se porque en los label --}}
        <input type="file" name="imagefile" id="imagefile" accept="image/*" onchange="preview(event)"
            style="display:none" value="{{ old('image_file', isset($product) ? $product->image_file : '') }}">

        <div id="display-image">
            <img src="{{ isset($product) ? asset($product->image_file) : '' }}"
                alt="{{ isset($product) ? 'Imagen no Disponible' : 'Seleccione una Imagen' }} " height="150px"
                width="150px" />
        </div>
        @error('image_file')
            <span class="text-danger"> {{ $message }} </span> <br />
        @enderror

    </div>
</div>
