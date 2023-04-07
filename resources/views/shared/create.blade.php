@extends('layouts.app', ['activePage' => $config['layout']['activePage'], 'titlePage' => __($config['layout']['titlePage'])])

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="content" style="margin-top: 40px">

        {{-- Inputs usado para buscar en searchCoins verificar si se pueden eliminar calc_currency_id se usa en el controller de payment --}}
        <input type="hidden" id="base_currency_id" value="{{ $config['data']['baseCoin']->id }}">
        {{-- el input last_rate se usa cuando se cambie la tasa para el recalculo --}}
        <input type="hidden" id="last_rate" name="last_rate">
        <div class="row">
            <div class="col-sm-12">
                <form method="post" action="{{ route($config['router']['routePost']) }}" autocomplete="off"
                    class="form-horizontal">
                    @csrf
                    <input type="hidden" value="{{ auth()->user()->id }}" name="user_id">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <x-header-index-filter :config="$config"></x-header-index-filter>
                            <x-message-error :hasError="$errors->has('mount')" :message="$errors->first('mount')" color="true"></x-message-error>
                            <input id="tax" name="tax_mount" type="hidden">
                            <input type="hidden" name="rate_exchange_date" id="rate"
                                value="{{ $config['data']['calcCoin']['rate'] }}">
                            <input type="hidden" name="calc_currency_id" id="calc_currency_id"
                                value="{{ $config['data']['calcCoin']->id }}">
                        </div>
                        <div class="card-body">
                            @php
                                $productId = old('product_id');
                                $quantity = old('quantity');
                                $price = old('price');
                                $tax = old('tax');
                                $taxId = old('tax_id');
                                $oldCoinId = old('coin_id');
                            @endphp
                            @include($config['include']['header'])
                            @if (!($config['router']['item'] == '$payment' && isset($invoice)))
                                @include($config['include']['detail'])
                            @endif
                        </div>
                        @if ($config['router']['item'] == '$payment')
                            <div class="row mt-2">
                                <div class="col-sm-5 mt-3"> <a href="{{ route($config['router']['routeIndex']) }}">
                                        {{ __('Ir a Listado') }} </a>
                                </div>
                                @if (!isset($invoice))
                                    <div class="col-sm-7">
                                        <button type="submit"
                                            class="btn btn-primary">{{ __($config['header']['messageSave']) }}</button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection


@push('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js') }}/globalvars.js"></script>
    <script src="{{ asset('js') }}/searchfunctions.js"></script>
    <script src="{{ asset('js') }}/tabledetails.js"></script>

    <script>
        collections = @json($config['data']);
        urlBase = "{{ config('app.url') }}";

        window.addEventListener("DOMContentLoaded", () => {
            $('.select2').select2();
            oldCoin = @json($oldCoinId);
            if (!collections.update && oldCoin == null) {
                document.getElementById('coin_id').value = collections.calcCoin.id
            }
            variables = @json($config['var_header']); // busca informacion del controlador utilizado
            if (variables.controller == 'Pago') {
                if (document.getElementById(variables.name).value > 0) {
                    SearchIdPayment(variables.name)
                }
            } else { // en caso de compras verifica si existen productos
                productos = @json($productId);
                quantities = @json($quantity);
                prices = @json($price);
                taxes = @json($tax);
                taxes_id = @json($taxId);
                if (productos != null) {
                    subtotal = [];
                    for (idx = 0; idx < productos.length; idx++) {
                        productCurrent = collections.products.filter(product => product.id == productos[idx])
                        productMount = parseFloat(quantities[idx]) * parseFloat(prices[idx]) + parseFloat(taxes[
                            idx])
                        let renglon = {
                            "item": idx + 1,
                            "product_id": productos[idx],
                            "producto": productCurrent[0].name,
                            "quantity": parseFloat(quantities[idx]),
                            "precio": parseFloat(prices[idx]),
                            "precio_other": 0,
                            "tax_id": taxes_id[idx],
                            "tax": parseFloat(taxes[idx]),
                            'tax_percent': 0,
                            "monto": productMount
                        }
                        LoadItem(renglon)
                    }
                }
            }

        })

        function ChangeCoin(option, valor) {
            let calc_coin_id = collections.calcCoin.id
            coin = SearchCoin(document.getElementById('coin_id').value);
            tasa = collections.hasOwnProperty("suppliers") ? coin[0].purchase_price : coin[0].sale_price;
            document.getElementById('rate_exchange').value = tasa
            document.getElementById('last_rate').value = tasa > 1 ? tasa : document.getElementById('last_rate').value
            tasa = tasa == 1 ? document.getElementById('rate').value : tasa
            if (option == "Proveedor" || option == "Cliente") {
                if (total > 0) {
                    RecalculateInvoice(true)
                }
                if (document.getElementById('pidproduct').value > 0) {
                    precio = document.getElementById("productPrice").value;
                    precioOther = document.getElementById("productPriceOther").value
                    costos = document.getElementById("other_mount").value
                    subTotal = coin[0].id == calc_coin_id ? precio / tasa : precio * tasa
                    document.getElementById("productPrice").value = cpf(parseFloat(subTotal).toFixed(2), false)
                    subTotal = coin[0].id == calc_coin_id ? precioOther * tasa : precioOther / tasa
                    document.getElementById("productPriceOther").value = subTotal
                    CalcSubTotal()
                }
            } else {
                UpdateMountPayment()
            }
        }

        function ProductPrice(tipo, event) {
            let tasa = (document.getElementById('rate_exchange').value == 1 ? document.getElementById('rate').value :
                document.getElementById(
                    'rate_exchange').value);
            let coin_id = document.getElementById('coin_id').value;
            let base_coin_id = collections.baseCoin.id
            let calc_coin_id = collections.calcCoin.id

            productos = collections.products;
            product = productos.filter(producto => producto.id == event.target.value)
            let precio = 0
            if (tasa > 0) {
                let id = document.getElementById("pidproduct").value;
                if (id > 0) {
                    if (tipo == "Cliente") {
                        precio = parseFloat(coin_id == calc_coin_id ? product[0].sale_price : product[0].sale_price * tasa)
                            .toFixed(2);
                        precioOther = parseFloat((coin_id == calc_coin_id ? precio * tasa : precio / tasa)).toFixed(
                            2);
                    } else { // ojo aqui es precio costo del producto
                        precio = parseFloat(coin_id == calc_coin_id ? product[0].cost_price : product[0].cost_price * tasa)
                            .toFixed(2);
                        precioOther = parseFloat(coin_id == calc_coin_id ? precio * tasa : precio / tasa).toFixed(2);
                    }
                    // document.getElementById("pprecio").value = precio
                    // document.getElementById("pprecioother").value = precioOther
                    document.getElementById("productPrice").value = precio
                    document.getElementById("productPriceOther").value = precioOther

                    document.getElementById("ptax").value = product[0].tax.percent;
                    document.getElementById("ptax_id").value = product[0].tax_id;
                }
            } else {
                alert("Debe seleccionar la moneda de la Factura de Compra")
            }
        }

        function CalcSubTotal(field) {
            let totales = {
                'quantity': 0,
                'price': 0,
                'tax': 0,
                'price_other': 0
            };
            coin = LoadCoins()

            let tasa = (document.getElementById("rate_exchange").value > 1 ? document.getElementById("rate_exchange")
                .value : document.getElementById(
                    "rate").value)
            totales.quantity = document.getElementById("productQty").value;
            totales.price = document.getElementById("productPrice").value;
            totales.price_other = document.getElementById("productPriceOther").value;
            switch (field) {
                case 'PriceOther':
                    productPrice = (coin.current.id == coin.calculation.id ?
                        totales.price_other / tasa : totales.price_other * tasa)
                    document.getElementById("productPrice").value = cpf(parseFloat(productPrice).toFixed(2))
                    break;
                case 'Price':
                    productPriceOther = (coin.current.id == coin.calculation.id ?
                        totales.price * tasa : totales.price / tasa)
                    document.getElementById("productPriceOther").value = cpf(parseFloat(productPriceOther).toFixed(2))
                default:
                    break;
            }
            totales.price = document.getElementById("productPrice").value;
            totales.price_other = document.getElementById("productPriceOther").value;
            totales.tax = document.getElementById("ptax").value;
            if (totales.quantity > 0 && totales.price > 0) {
                let montototal = totales.quantity * totales.price + totales.price * (totales.tax / 100);
                let monto = parseFloat(montototal).toFixed(2)
                document.getElementById("productSubTotal").innerHTML = cpf(monto, false)
            }
        }
    </script>
@endpush
