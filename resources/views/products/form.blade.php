<div class="row mt-3">
    <div class="col-sm-10">
        <div class="row">
            <div class="col-sm-2 col-md-1 mt-2">
                <label>{{ __('Codigo') }}</label>
            </div>
            <div class="col-sm-9 col-md-3 ml-sm-0">
                <input class="form-control" name="code" id="input-code" type="text"
                    placeholder="{{ __('Codigo del Producto') }}"
                    value="{{ old('code', isset($product) ? $product->code : '') }}" required aria-required="false">
                <x-message-error :hasError="$errors->has('code')" :message="$errors->first('code')"></x-message-error>
            </div>
            <div class="col-sm-3 col-md-2 mt-2">
                <label> {{ __('Descripción') }} </label>
            </div>
            <div class="col-sm-8 col-md-6">
                <input class="form-control" name="name" id="input-name" type="text"
                    placeholder="{{ __('Ingrese nombre') }}"
                    value="{{ old('name', isset($product) ? $product->name : '') }}" required aria-required="false">
                <x-message-error :hasError="$errors->has('name')" :message="$errors->first('name')"></x-message-error>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-5 col-md-3 col-lg-1 mt-2">
                <label>{{ __('Categoria: ') }}</label>
            </div>
            <div class="col-sm-7 col-md-5 col-xl-4 ml-lg-3">
                <select name="category_id" id="category_id" class="form-control"
                    onchange="findGroup(event.target.value)">
                    <option value=0> Seleccione la Categoria ... </option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id') == $category->id || (isset($product) && $product->productCategory->id == $category->id) ? 'selected' : '' }}>
                            {{ $category->description }} </option>
                    @endforeach
                </select>
                <x-message-error :hasError="$errors->has('category_id')" :message="$errors->first('category_id')"></x-message-error>
                </select>
            </div>
            <div class="col-sm-4 col-md-2 mt-2">
                <label>{{ __('Grupo Producto') }}</label>
            </div>
            <div class="col-sm-8 col-md-2">
                <input class="form-control" name="label-group" id="label-group" type="text"
                    value="{{ old('label-group', isset($product) ? $product->ProductCategory->ProductGroup->description : '') }}"
                    aria-required="false" disabled>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-4 col-md-1 col-lg-2 mt-2">
                <label>{{ __('Tipo Impuesto') }}</label>
            </div>
            <div class="col-sm-7 col-md-2">
                <select name="tax_id" id="tax_id" class="form-control">
                    <option value=0> Seleccione un tipo de impuesto ... </option>
                    @foreach ($taxes as $tax)
                        <option value="{{ $tax->id }}"
                            {{ old('tax_id') == $tax->id || (isset($product) && $product->tax->id == $tax->id) ? 'selected' : '' }}>
                            {{ $tax->description }} </option>
                    @endforeach
                </select>
                @error('tax_id')
                    <span class="text-danger"> {{ $message }} </span> <br />
                @enderror
            </div>
            <div class="col-sm-2 col-md-2 mt-2">
                <label>{{ __('Precio Costo') }}
                    {{ $config['purchaseCoin']->symbol }}</label>
            </div>

            <div class="col-sm-4 col-md-2">
                <input class="form-control" name="cost_price" id="cost_price" type="number" step="any"
                    value="{{ old('cost_price', isset($product) ? $product->cost_price : '') }}" required
                    aria-required="false"
                    onkeyup="document.getElementById('cost_price_other').value = parseFloat(document.getElementById('cost_price').value * {{ $config['purchaseCoin']->purchase_price }}).toFixed(2) ">
                @if ($config['purchaseCoin']->id != $config['baseCoin']->id)
                    <input class="form-control" name="cost_price_other" id="cost_price_other" type="number"
                        step="any"
                        value="{{ old('cost_price', isset($product) ? number_format($product->cost_price * $config['purchaseCoin']->purchase_price, 2) : '') }}"
                        required aria-required="false"
                        onkeyup="
                                document.getElementById('cost_price').value = parseFloat
                                            (document.getElementById('cost_price_other').value / {{ $config['purchaseCoin']->purchase_price }}).toFixed(2)">
                @endif
                @error('cost_price')
                    <span class="text-danger"> {{ $message }} </span> <br />
                @enderror
            </div>
            <div class="col-sm-2 mt-2">
                <label>{{ __('Precio Venta') }} {{ $config['saleCoin']->symbol }}
                </label>
            </div>
            <div class="col-sm-2">
                <input class="form-control {{ $errors->has('sale_price') ? ' is-invalid' : '' }}" name="sale_price"
                    id="sale_price" type="number" step="any"
                    value="{{ old('sale_price', isset($product) ? $product->sale_price : '') }}" required
                    aria-required="false"
                    onkeyup=" document.getElementById('sale_price_other').value = parseFloat(document.getElementById('sale_price').value * {{ $config['saleCoin']->sale_price }}).toFixed(2)   ">
                @if ($config['saleCoin']->id != $config['baseCoin']->id)
                    <input class="form-control {{ $errors->has('sale_price') ? ' is-invalid' : '' }}"
                        name="sale_price_other" id="sale_price_other" type="number" step="any"
                        value="{{ old('sale_price', isset($product) ? number_format($product->sale_price * $config['saleCoin']->sale_price, 2) : '') }}"
                        required aria-required="false"
                        onkeyup=" document.getElementById('sale_price').value = parseFloat(document.getElementById('sale_price_other').value / {{ $config['saleCoin']->sale_price }}).toFixed(2)   ">
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
        {{-- <button class="btn-image" onclick="document.getElementById('imagefile').click()"> Leer Archivo</button>
            --}}
        {{-- El id y name de type file no permitio camelCase ni snake_case no se porque en los label --}}
        <input type="file" name="image_file" id="imagefile" accept="image/*" onchange="preview(event)"
            style="display:none" value="{{ old('image_file', isset($product) ? $product->image_file : '') }}">

        <div id="display-image">
            <img src="{{ isset($product) ? asset($product->image_file) : '' }}"
                alt="{{ isset($product) ? asset($product->image_file) : 'Seleccione una Imagen' }} " height="120vh"
                width="100%" />
        </div>
        @error('image_file')
            <span class="text-danger"> {{ $message }} </span> <br />
        @enderror

    </div>
</div>
