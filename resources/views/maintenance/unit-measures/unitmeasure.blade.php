{{--  Fomrulario para cuando toque agregar la distribución de productos por unidad
            <div class="col-6 border text-center">
                <div class="row bg-info text-primary ">
                    <label for="" class="col-12 form-labe text-white">Costos Asociados</label>
                </div>
                <div class="row">
                    <label for="" class="col-5 form-label">Unidad</label>
                    <label for="" class="col-3 form-label">Cant.</label>
                    <label for="" class="col-4 form-label">Costo</label>
                </div>
                @include('maintenance.unit-measures.unitmeasure')
                @include('maintenance.unit-measures.unitmeasure')
                @include('maintenance.unit-measures.unitmeasure')
            </div>
            <div class="col-6 border text-center">
                <div class="row bg-info text-primary ">
                    <label for="" class="col-12 form-labe text-white">Precios Asociados</label>
                </div>
                <div class="row">
                    <label for="" class="col-5 form-label">Unidad</label>
                    <label for="" class="col-3 form-label">Cant.</label>
                    <label for="" class="col-4 form-label">Costo</label>
                </div>
                @include('maintenance.unit-measures.unitmeasure')
                @include('maintenance.unit-measures.unitmeasure')
                @include('maintenance.unit-measures.unitmeasure')
            </div>
 --}}




<div class="row ">
    <div class="col-4">
        <select name="unit_measure[]" id="unit_measure[]" class="form-control">
            <option value = 0> Unidad de Medida  ... </option>
            @foreach ($unitmeasures as $unitmeasure)
                <option value = "{{ $unitmeasure->id }}">
                    {{ $unitmeasure->description }} </option>
            @endforeach
        </select>
    </div>
    <div class="col-4">
        <input class="form-control {{ $errors->has('code') ? ' is-invalid' : '' }}"
        name="cantidad[]" id="input-cantidad[]" type="number" placeholder="{{ __('Cantidad de la Unidad') }}"
        value = "">
        @error('cantidad[]')
            <span class="text-danger"> {{$message}} </span> <br/>
        @enderror

    </div>
    <div class="col-4">
        <input class="form-control {{ $errors->has('code') ? ' is-invalid' : '' }}"
        name="costo[]" id="input-costo[]" type="number" placeholder="{{ __('Costo del Producto ') }}"
        value = "">
        @error('costo[]')
            <span class="text-danger"> {{$message}} </span> <br/>
        @enderror
    </div>
</div>
