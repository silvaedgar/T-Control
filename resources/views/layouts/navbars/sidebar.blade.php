<div class="sidebar" data-color="purple" data-background-color="azure"
    data-image="{{ asset('material') }}/img/sidebar-1.jpg">

    <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
    <div class="logo">
        <div class="text-center">
            <span class="h4"> {{ __('T-Control Sistema') }} </span><br>
        </div>
        <div class="small">
            <div class="row">
                <div class="col-8">
                    <span class="small">Usuario:{{ Auth::user()->name }} </span>
                </div>
                <div class="col-4 small justify-end">
                    <a class="small justify-end " href="{{ route('logout') }}" style="display: inline"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit()">
                        <i class="material-icons">logout</i> Salir
                    </a>
                </div>
            </div>

        </div>

    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item{{ $activePage == 'Inicio' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="material-icons">dashboard</i>
                    <p>{{ __('Menu Principal') }}</p>
                </a>
            </li>
            @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('User'))
                <li
                    class="nav-item  {{ $activePage == 'purchases' || $activePage == 'user-management' ? ' active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#laravelExample"
                        aria-expanded="{{ $activePage == 'purchases' }}">
                        <i class="material-icons">shopping_cart</i>
                        <p>{{ __('Modulo de Compras') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ $activePage == 'purchases' ? 'show' : 'hide' }}" id="laravelExample">
                        <ul class="nav">
                            <li class="nav-item{{ $titlePage == 'Modulo de Proveedores' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('suppliers.index') }}">
                                    <span class="sidebar-mini"> <i class="material-icons">groups</i></span>
                                    <span class="sidebar-normal">{{ __('Proveedores') }} </span>
                                </a>
                            </li>
                            <li class="nav-item{{ $titlePage == 'Modulo de Compras' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('purchases.index') }}">
                                    <i class="material-icons">receipt</i>
                                    <span class="sidebar-normal"> {{ __('Factura de Compra') }} </span>
                                </a>
                            </li>
                            <li class="nav-item{{ $titlePage == 'Modulo de Pago a Proveedores' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('paymentsuppliers.index') }}">
                                    <i class="material-icons">attach_money</i>
                                    <span class="sidebar-normal"> {{ __('Pagos a Proveedor') }} </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li
                    class="nav-item {{ $activePage == 'sales' || $activePage == 'user-management' ? ' active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#ventas"
                        aria-expanded="{{ $activePage == 'sales' }}">
                        <i class="material-icons">point_of_sale</i>
                        <p>{{ __('Modulo de Facturación') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ $activePage == 'sales' ? 'show' : 'hide' }}" id="ventas">
                        <ul class="nav">
                            <li class="nav-item{{ $titlePage == 'Modulo de Clientes' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('clients.index') }}">
                                    <i class="material-icons">group</i>
                                    <span class="sidebar-normal">{{ __('Clientes') }} </span>
                                </a>
                            </li>
                            <li class="nav-item{{ $titlePage == 'Modulo de Ventas' ? ' active' : '' }}">
                                <a class="nav-link" href=" {{ route('sales.index') }}">
                                    <i class="material-icons">shopping_cart</i>
                                    <span class="sidebar-normal"> {{ __('Facturar') }} </span>
                                </a>
                            </li>
                            <li class="nav-item{{ $titlePage == 'Modulo de Pago de Clientes' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('paymentclients.index') }}">
                                    <i class="material-icons">attach_money</i>
                                    <span class="sidebar-normal"> {{ __('Pagos de Clientes') }} </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li
                    class="nav-item {{ $activePage == 'store' || $activePage == 'user-management' ? ' active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#almacen"
                        aria-expanded={{ $activePage == 'store' }}>
                        <i class="material-icons">store</i>
                        <p>{{ __('Modulo de Almacen') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ $activePage == 'store' ? 'show' : 'hide' }}" id="almacen">
                        <ul class="nav">
                            <li class="nav-item{{ $titlePage == 'Modulo de Productos' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('products.index') }}">
                                    <i class="material-icons">inventory</i>
                                    <span class="sidebar-normal">{{ __('Productos') }} </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if (auth()->user()->hasRole('Admin'))
                    <li
                        class="nav-item {{ $activePage == 'maintenance' || $activePage == 'user-management' ? ' active' : '' }}">
                        <a class="nav-link" data-toggle="collapse" href="#mantenimiento"
                            aria-expanded="{{ $activePage == 'maintenance' }}">
                            <i class="material-icons">engineering</i>
                            <p>{{ __('Mantenimiento') }}<b class="caret"></b></p>
                        </a>
                        <div class="collapse {{ $activePage == 'maintenance' ? 'show' : 'hide' }} backgroundcollapse"
                            id="mantenimiento">
                            <ul class="nav">
                                <li
                                    class="nav-item{{ $titlePage == 'Modulo Grupos de Productos' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('maintenance.productgroups.index') }}">
                                        <i class="material-icons">article</i>

                                        <span class="sidebar-normal">{{ __('Grupos  de Productos') }} </span>
                                    </a>
                                </li>
                                <li
                                    class="nav-item{{ $titlePage == 'Modulo Categorias  de Productos' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('maintenance.productcategories.index') }}">
                                        <i class="material-icons">category</i>
                                        <span class="sidebar-normal"> {{ __('Categorias de Productos') }} </span>
                                    </a>
                                </li>
                                <li class="nav-item{{ $titlePage == 'Modulo Formas de Pago' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('maintenance.paymentforms.index') }}">
                                        <i class="material-icons">payment</i>
                                        <span class="sidebar-normal"> {{ __('Formas de Pago') }} </span>
                                    </a>
                                </li>

                                <li class="nav-item{{ $titlePage == 'Modulo de Monedas' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('maintenance.coins.index') }}">
                                        <i class="material-icons">monetization_on</i>
                                        <span class="sidebar-normal"> {{ __('Monedas') }} </span>
                                    </a>
                                </li>
                                <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('coinbase.index') }}">
                                        <i class="material-icons">paid</i>
                                        <span class="sidebar-normal" style="font-size: smaller">
                                            {{ __('Moneda base y Calculo') }} </span>
                                    </a>
                                </li>
                                <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('maintenance.currencyvalues.index') }}">
                                        <i class="material-icons">currency_exchange</i>
                                        <span class="sidebar-normal" style="font-size: smaller">
                                            {{ __('Relación Precio Compra-Venta') }} </span>
                                    </a>
                                </li>

                                <li class="nav-item{{ $titlePage == 'Modulo de Impuestos' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('maintenance.taxes.index') }}">
                                        <i class="material-icons">money</i>
                                        <span class="sidebar-normal"> {{ __('Impuestos') }} </span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item{{ $titlePage == 'Modulo de Unidad de Medida' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('maintenance.unitmeasures.index') }}">
                                        <i class="material-icons">scale</i>
                                        <span class="sidebar-normal"> {{ __('Unidades de Medida') }} </span>
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <li
                        class="nav-item {{ $activePage == 'users' || $activePage == 'user-management' ? ' active' : '' }}">
                        <a class="nav-link" data-toggle="collapse" href="#users">
                            <i class="material-icons">group</i>
                            <p>{{ __('Control de Usuarios') }}<b class="caret"></b></p>
                        </a>

                        <div class="collapse {{ $activePage == 'users' ? 'show' : 'hide' }} backgroundcollapse"
                            id="users">
                            <ul class="nav">
                                <li class="nav-item{{ $titlePage == 'Modulo de Usuarios' ? ' active' : '' }}">
                                    <a class="nav-link" href=" {{ route('users.index') }}">
                                        <i class="material-icons">people_alt</i>

                                        <span class="sidebar-normal">{{ __('Usuarios') }} </span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item{{ $titlePage == 'Modulo de Usuarios Clientes' ? ' active' : '' }}">
                                    <a class="nav-link" href=" {{ route('userclients.index') }}">
                                        <i class="material-icons">article</i>
                                        <span class="sidebar-normal">{{ __('Activar Clientes como Usuarios') }}
                                        </span>
                                    </a>
                                </li>

                                {{-- <li class="nav-item{{ $titlePage == 'Modulo de Roles' ? ' active' : '' }}">
                                <a class="nav-link" href=" {{ route ('roles.index') }}">
                                <i class="material-icons">supervisor_account</i>
                                <span class="sidebar-normal">{{ __('Roles de Usuarios') }} </span>
                                </a>
                            </li> --}}
                            </ul>
                        </div>
                    </li>
                @endif
            @else
                {{-- <li class="nav-item {{ ($activePage == 'accountstatus' || $activePage == 'user-management') ? ' active' : '' }}"> --}}
                <li class="nav-item{{ $titlePage == 'Estados de Cuenta' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('clients.account_state', Auth::user()->id) }}">
                        <i class="material-icons">account_balance</i>
                        <span class="sidebar-normal">{{ __('Estado de Cuenta') }} </span>
                    </a>
                </li>
                <li class="nav-item{{ $titlePage == 'Actualizar Datos' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('users.edit', Auth::user()->id) }}">
                        <i class="material-icons">description</i>
                        <span class="sidebar-normal">{{ __('Actualizar Datos') }} </span>
                    </a>
                </li>

                {{-- </li> --}}


            @endif
        </ul>
    </div>
</div>
