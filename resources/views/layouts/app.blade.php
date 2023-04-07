<!DOCTYPE html>
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> --}}
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> {{ config('app.name', 'Laravel') }}</title>
    {{-- __('Vesil C.A') --}}
    <link rel="icon" type="image/png" href=" {{ asset('/') }}vesil.png">

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    @yield('css')
    <!-- CSS Files -->
    <link href="{{ asset('css') }}/styles.css" rel="stylesheet">
    <link href="{{ asset('material') }}/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    {{--
    <link href="{{ asset('material') }}/demo/demo.css" rel="stylesheet" /> --}}
</head>

<body class="{{ isset($class) ? $class : 'bg-info' }}">
    @auth()
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        @include('layouts.page_templates.auth')
    @endauth
    @guest()
        @include('layouts.page_templates.guest')
    @endguest
    {{-- @if (auth()->check())
    <div class="fixed-plugin">
        <div class="dropdown show-dropdown">
            <a href="#" data-toggle="dropdown">
                <i class="fa fa-cog fa-2x"> </i>
            </a>
            <ul class="dropdown-menu">
                <a href="javascript:void(0)" class="switch-trigger active-color">
                    <div class="badge-colors ml-auto mr-auto">
                        <span class="badge filter badge-purple " data-color="purple"></span>
                        <span class="badge filter badge-azure" data-color="azure"></span>
                        <span class="badge filter badge-green" data-color="green"></span>
                        <span class="badge filter badge-warning active" data-color="orange"></span>
                        <span class="badge filter badge-danger" data-color="danger"></span>
                        <span class="badge filter badge-rose" data-color="rose"></span>
                </a>
            </ul>
        </div>
    </div>
    @endif --}}
    <!--   Core JS Files   -->
    <script src="{{ asset('material') }}/js/core/jquery.min.js"></script>
    <script src="{{ asset('material') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('material') }}/js/core/bootstrap-material-design.min.js"></script>
    <script src="{{ asset('material') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!-- Plugin for the momentJs  -->
    <script src="{{ asset('material') }}/js/plugins/moment.min.js"></script>
    <!--  Plugin for Sweet Alert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="{{ asset('material') }}/js/plugins/sweetalert2.js"></script> --}}
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
    {{-- <script src="{{ asset('material') }}/js/plugins/chartist.min.js"></script> --}}
    <!--  Notifications Plugin    -->
    <script src="{{ asset('material') }}/js/plugins/bootstrap-notify.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('material') }}/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
    <!-- Material Dashboard DEMO methods, don't include it in your project! -->
    {{-- <script src="{{ asset('material') }}/demo/demo.js"></script> --}}
    <script src="{{ asset('material') }}/js/settings.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script lang="text/javascript">
        $('.delete-item').submit(function(e, item) {
            e.preventDefault();
            Swal.fire({
                title: 'Seguro de Eliminar el Registro',
                text: 'Esta Accion no se podrá deshacer',
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.value) {
                    this.submit();
                }
            })
        })

        if (document.getElementById('mensaje')) {
            message = document.getElementById('mensaje').value;
            if (message != "") {
                array_message = message.split('_');
                message = array_message[1];
                title = (array_message[0] == "Ok") ? 'Proceso Exitoso' : 'Proceso Fallido';
                icon = (array_message[0] == "Ok") ? 'success' : 'error';
                Swal.fire(
                    title,
                    message,
                    icon
                )
            }
        }

        $('#logout-form').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Salir del Sistema? ',
                text: "Seguro??",
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.value) {
                    this.submit()
                }
            })
        })
    </script>
    @stack('js')
</body>

</html>
