<input type="hidden" value ="{{isset($product) ? old('id',$product->id) : 0}}" name = "id">
<input type="hidden" value ="{{isset($product) ? old('user_id',$product->user_id) : auth()->user()->id }}" name = "user_id">
<input type="hidden" value ="{{isset($product) ? old('category_id',$product->category_id) : 0 }}" id = "category_id">

{{-- <div class="col-12 col-md-4 col-sm-1"> --}}
<div class="row">
    <div class="col-sm-10">
        <div class="row">
            <div class="col-sm-3 col-md-1">
                <label class="col-form-label">{{ __('Codigo') }}</label>
            </div>
            <div class="col-sm-9 col-md-3">
                <input class="form-control " name="code" id="input-code" type="text"
                    placeholder="{{ __('Codigo del Producto') }}"
                    value="{{ old('code', (isset($product) ? $product->code :''))}}" required aria-required="false">
                @error('code')
                    <span class="text-danger"> {{$message}} </span> <br/>
                @enderror
            </div>
            <div class="col-sm-3 col-md-2">
                <label class="col-form-label"> {{ __('Descripción') }} </label>
            </div>
            <div class="col-sm-8 col-md-6">
                <input class="form-control"
                name="name" id="input-name" type="text" placeholder="{{ __('Ingrese nombre') }}"
                value="{{ old('name', (isset($product) ? $product->name :''))}}" required aria-required="false">
                @error('name')
                    <span class="text-danger"> {{$message}} </span> <br/>
                @enderror
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-4 col-md-2">
                <label class="col-form-label">{{ __('Grupo Producto') }}</label>
            </div>
            <div class="col-sm-8 col-md-2">
                <select name="group_id" id = "group-id" class="form-control"  onchange="LoadCategories()" >
                    <option value = 0> Seleccione un Grupo ... </option>
                    @foreach ($groups as $group)
                        <option
                            @if (isset($group_id))
                                @if ($group_id[0]->id == $group->id)
                                    selected
                                @endif
                            @endif
                            value = "{{ $group->id }}">
                            {{ $group->description }} </option>
                    @endforeach
                </select>
                @error('group_id')
                    <span class="text-danger"> {{$message}} </span> <br/>
                @enderror
            </div>
            <div class="col-sm-5 col-md-3">
                <label class="col-form-label">{{ __('Categoria Producto') }}</label>
            </div>
            <div class="col-sm-7 col-md-5 col-xl-4">
                <select name="category_id" id="category-id" class="form-control"  >
                    <option value = 0> Seleccione la Categoria ... </option>
                </select>
                    @error('category_id')
                        <span class="text-danger"> {{$message}} </span> <br/>
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
                        <option value = 0> Seleccione un tipo de impuesto ... </option>
                        @foreach ($taxes as $tax)
                            <option value = "{{ $tax->id }}"
                                @if (isset($product))
                                    @if ($product->tax_id == $tax->id)
                                        selected
                                    @endif
                                @endif
                                > {{ $tax->description }} </option>
                        @endforeach
                </select>
                @error('tax_id')
                    <span class="text-danger"> {{$message}} </span> <br/>
                @enderror
            </div>
            <div class="col-sm-2 col-md-2">
                <label class="col-form-label">{{ __('Precio Costo') }}</label>
            </div>
            <div class="col-sm-4 col-md-2 ">
                <input class="form-control"
                    name="cost_price" id="input-cost_price" type="number" step = "any"
                    value="{{ old('cost_price', (isset($product) ? $product->cost_price :''))}}" required aria-required="false">
                @error('cost_price')
                    <span class="text-danger"> {{$message}} </span> <br/>
                @enderror
            </div>
            <div class="col-sm-2">
                <label class="col-form-label">{{ __('Precio Venta') }}</label>
            </div>
            <div class="col-sm-3">
                <input class="form-control {{ $errors->has('sale_price') ? ' is-invalid' : '' }}"
                    name="sale_price" id="input-sale_price" type="number" step = "any"
                    value="{{ old('sale_price', (isset($product) ? $product->sale_price :''))}}" required aria-required="false">
                @error('sale_price')
                    <span class="text-danger"> {{$message}} </span> <br/>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-sm-2 border">
        <label for="imagefile"> <i class="fa fa-plus" aria-hidden="true"
            style="font-size:15px;  padding: 5px; cursor: pointer; margin-left:-15px; ">
        </i></label>
        {{-- <button class="btn-image" onclick="document.getElementById('imagefile').click()"> Leer Archivo</button> --}}
        <input type="file" name="imagefile" id="imagefile" accept="image/*"
            onchange="preview(event)" style = "display:none">
        <div id="display-image">
        </div>
        @error('imagefile')
            <span class="text-danger"> {{$message}} </span> <br/>
        @enderror

    </div>
</div>
