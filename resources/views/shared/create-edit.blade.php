@extends('layouts.app', ['activePage' => $config['layout']['activePage'], 'titlePage' => __($config['layout']['titlePage'])])

@section('css')
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content " style="margin-top: 40px; overflow-y: scroll ">
        <div class="row">
            <div class="col-md-12">
                @if ($config['data']['collection'] != null)
                    <form action="{{ route($config['router']['routePost'], $config['router']['item']) }}" autocomplete="off"
                        class="form-horizontal" enctype="multipart/form-data" method="post">
                        @method('put')
                    @else
                        <form action="{{ route($config['router']['routePost']) }}" autocomplete="off"
                            class="form-horizontal" method="post">
                @endif

                @csrf
                <input name="id" type="hidden"
                    value="{{ old('id', isset($config['data']['collection']) ? $config['data']['collection']->id : 0) }}">
                <input name="user_id" type="hidden"
                    value="{{ isset($config['data']['collection']) ? old('user_id', $config['data']['collection']->user_id) : auth()->user()->id }}">
                <div class="card mx-auto">
                    <div class="card-header card-header-primary">
                        @include('shared.header')
                    </div>
                    <div class="card-body">
                        @include($config['header']['form'])
                    </div>
                    <div class="card-footer ml-auto mr-auto">
                        <button class="btn btn-primary" type="submit">{{ __('Grabar') }}</button>
                    </div>
                    </form>
                    @if (isset($config['data']['purchases']))
                        <div class="collapse" id="collapseExample">
                            @include('products.last-purchases')
                        </div>
                    @endif
                    <div>
                        <a class="ms-3" href="{{ route($config['router']['routeIndex']) }}"> {{ __('Volver al listado') }}
                        </a>
                        @if (isset($config['data']['purchases']))
                            <a class="float-end px-5" data-bs-toggle="collapse" href="#collapseExample" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                Ver Ultimas Compras
                            </a>
                        @endif

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js') }}/globalvars.js"></script>
    <script src="{{ asset('js') }}/searchfunctions.js"></script>
    <script>
        function findGroup(value) {
            let categories = <?php echo isset($categories) ? $categories : ''; ?>
            //categories = JSON.parse($categories);
            console.log(categories)
            category = categories.filter(group => group.id == value)
            document.getElementById('label-group').value = category[0].product_group.description
        }

        window.addEventListener('DOMContentLoaded', () => {
            let data = @json($config['data']);
            switch (data.controller) {
                case 'Product':
                    if (data.update) {
                        console.log("DATA", document.getElementById('category_id').value)
                        document.getElementById('category_id').value = data.product.product_category.id;
                        document.getElementById('tax_id').value = data.product.tax_id
                    }
                    break;
                default:
                    break;
            }

        })
    </script>
@endpush
