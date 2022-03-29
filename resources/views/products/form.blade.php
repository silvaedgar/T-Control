<input type="hidden" value ="{{isset($product) ? old('id',$product->id) : 0}}" name = "id">
<input type="hidden" value ="{{isset($product) ? old('user_id',$product->user_id) : auth()->user()->id }}" name = "user_id">
<input type="hidden" value ="{{isset($product) ? old('category_id',$product->category_id) : 0 }}" id = "category_id">

{{-- <div class="col-12 col-md-4 col-sm-1"> --}}
<div class="row">
    <div class="col-9">    {{--col-md-4 col-sm-1--}}
        <div class="row">
            <label class="col-3 col-form-label">{{ __('Codigo  Producto') }}</label>
            <label class="col-4 col-form-label">{{ __('Nombre Producto') }}</label>
        </div>
        <div class="row">
            <div class="col-3">
                <input class="form-control {{ $errors->has('code') ? ' is-invalid' : '' }}"
                    name="code" id="input-code" type="text" placeholder="{{ __('Ingrese codigo') }}"
                    value="{{ old('code', (isset($product) ? $product->code :''))}}" required aria-required="false">
                @error('code')
                    <span class="text-danger"> {{$message}} </span> <br/>
                @enderror
            </div>
            <div class="col-9">
                <input class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                    name="name" id="input-name" type="text" placeholder="{{ __('Ingrese nombre') }}"
                    value="{{ old('name', (isset($product) ? $product->name :''))}}" required aria-required="false">
                @error('code')
                    <span class="text-danger"> {{$message}} </span> <br/>
                @enderror
            </div>
        </div>
        <div class="row mt-2">
            <label class="col-4 col-form-label">{{ __('Grupo Producto') }}</label>
            <label class="col-4 col-form-label">{{ __('Categoria Producto') }}</label>
            <label class="col-4 col-form-label">{{ __('Tipo Impuesto') }}</label>
        </div>
        <div class="row">
            <div class="col-4">
                <select name="group_id" id="group-id" class="form-control"  onchange="LoadCategories()" >
                    <option value = 0> Seleccione un Grupo ... </option>
                    @foreach ($groups as $group)
                        <option value = "{{ $group->id }}"
                            @if (isset($group_id))
                                @if ($group_id[0]->id == $group->id)
                                    selected
                                @endif
                            @endif>
                            {{ $group->description }} </option>
                    @endforeach
                </select>
                @error('group_id')
                    <span class="text-danger"> {{$message}} </span> <br/>
                @enderror
            </div>
            <div class="col-4">
                {{-- @if (isset($category_id))
                @endif --}}
                <select name="category_id" id="category-id" class="form-control"  >
                    <option value = 0> Seleccione la Categoria ... </option>
                </select>
                    @error('category_id')
                        <span class="text-danger"> {{$message}} </span> <br/>
                    @enderror
                </select>
            </div>
            <div class="col-4">
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
        </div>
        <div class="row mt-2">
            <label class="col-6 col-form-label">{{ __('Precio Costo') }}</label>
            <label class="col-6 col-form-label">{{ __('Precio Venta') }}</label>
        </div>
        <div class="row">
                <div class="col-6">
                    <input class="form-control {{ $errors->has('cost_price') ? ' is-invalid' : '' }}"
                        name="cost_price" id="input-cost_price" type="number" step = "any"
                        value="{{ old('cost_price', (isset($product) ? $product->cost_price :''))}}" required aria-required="false">
                    @error('cost_price')
                        <span class="text-danger"> {{$message}} </span> <br/>
                    @enderror
                </div>
                <div class="col-6">
                    <input class="form-control {{ $errors->has('sale_price') ? ' is-invalid' : '' }}"
                        name="sale_price" id="input-sale_price" type="number" step = "any"
                        value="{{ old('sale_price', (isset($product) ? $product->sale_price :''))}}" required aria-required="false">
                    @error('sale_price')
                        <span class="text-danger"> {{$message}} </span> <br/>
                    @enderror
                </div>
        </div>
    </div>
    <div class="col-3 border">
        AQUI VA LA IMAGEN
    </div>
</div>
