<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    {{-- __('Vesil C.A') --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>

    {{-- Link del carrusel --}}
    <link rel="canonical" href="https://v5.getbootstrap.com/docs/5.0/examples/carousel/">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />

    {{--
    <link href="{{ asset('material') }}/css/material-dashboard.css?v=2.1.1" rel="stylesheet" /> --}}


    @yield('css')
</head>

<body class="{{ isset($class) ? $class : 'bg-info' }}">



    <nav class="navbar navbar-expand-lg navbar-light bg-info shadow fixed-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01"
                aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <img class="ml-4 mr-3" src="{{asset('/')}}\vesil.png" width="100" height="50">
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="{{ route('home-guest') }}" class="nav-link" style="color: white">
                            {{ __('Inicio') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('whoaim') }}" class="nav-link" style="color:white">
                            {{ __('Quienes Somos') }}
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link" style="color: white">
                            <i class="material-icons">person_add</i> {{ __('Registrarse') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link" style="color:white">
                            <i class="material-icons">fingerprint</i> {{ __('Login') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')

    <script src="{{ asset('material') }}/js/core/jquery.min.js"></script>
    <script src="{{ asset('material') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('material') }}/js/core/bootstrap-material-design.min.js"></script>
    <script src="{{ asset('material') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!-- Plugin for the momentJs  -->
    <script src="{{ asset('material') }}/js/plugins/moment.min.js"></script>
    <!--  Plugin for Sweet Alert -->
    <script src="{{ asset('material') }}/js/plugins/sweetalert2.js"></script>
    <!-- Forms Validations Plugin -->
    <script src="{{ asset('material') }}/js/plugins/jquery.validate.min.js"></script>
    <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
    <script src="{{ asset('material') }}/js/plugins/jquery.bootstrap-wizard.js"></script>
    <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
    <script src="{{ asset('material') }}/js/plugins/bootstrap-selectpicker.js"></script>
    <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
    <script src="{{ asset('material') }}/js/plugins/bootstrap-datetimepicker.min.js"></script>
    <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
    <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>
    <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
    <script src="{{ asset('material') }}/js/plugins/bootstrap-tagsinput.js"></script>
    <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
    <script src="{{ asset('material') }}/js/plugins/jasny-bootstrap.min.js"></script>
    <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
    <script src="{{ asset('material') }}/js/plugins/fullcalendar.min.js"></script>
    <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
    <script src="{{ asset('material') }}/js/plugins/jquery-jvectormap.js"></script>
    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="{{ asset('material') }}/js/plugins/nouislider.min.js"></script>
    <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <!-- Library for adding dinamically elements -->
    <script src="{{ asset('material') }}/js/plugins/arrive.min.js"></script>
    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE'"></script>
    <!-- Chartist JS -->
    <script src="{{ asset('material') }}/js/plugins/chartist.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="{{ asset('material') }}/js/plugins/bootstrap-notify.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('material') }}/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
    <!-- Material Dashboard DEMO methods, don't include it in your project! -->
    <script src="{{ asset('material') }}/demo/demo.js"></script>
    <script src="{{ asset('material') }}/js/settings.js"></script>


</body>

</html>