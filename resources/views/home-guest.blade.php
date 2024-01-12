@extends('layouts.app-guest', ['class' => 'bg-info', 'activePage' => 'dashboard', 'titlePage' => __('Inicio')])

@section('css')
    <style>
        .bd {
            font
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        body {
            padding-top: 3rem;
            padding-bottom: 3rem;
            color: #5a5a5a;
        }


        /* CUSTOMIZE THE CAROUSEL
            -------------------------------------------- */

        /* Carousel base class */
        .carousel {
            margin: auto;
        }

        /* Since positioning the image, we need to help out the caption */
        .carousel-caption {
            bottom: 3rem;
            z-index: 10;
        }

        /* Declare heights because of positioning of img element */
        .carousel-item {
            height: 25rem;
        }

        .carousel-item>img {
            position: absolute;
            top: 0;
            left: 0;
            min-width: 100%;
            height: 25rem;
        }


        /* RESPONSIVE CSS
        -------------------------------------------------- */

        @media (min-width: 40em) {

            /* Bump up size of carousel content */
            .carousel-caption p {
                /* margin-bottom: 1.25rem; */
                font-size: 1.25rem;
                line-height: 1.4;
            }

        }

        /* @media (min-width: 62em) {
              .featurette-heading {
                margin-top: 7rem;
              } */
        }
    </style>
@endsection


{{-- <nav class="navbar navbar-expand-lg navbar-absolute navbar-light bg-info fixed-top shadow">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <img class = "ml-4 mr-3" src="{{asset('/')}}\vesil.png" width = "100" height = "50">
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a href="{{ route('home-guest') }}" class="nav-link" style="color: white">
                        {{ __('Inicio') }}
                    </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('whoaim') }}" class = "nav-link" style="color:white">
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
                    <a href="{{ route('login') }}" class = "nav-link" style="color:white">
                            <i class="material-icons">fingerprint</i> {{ __('Login') }}
                    </a>
                    </li>
            </ul>
        </div>
    </div>
</nav> --}}

@section('content')
    <div class="container mt-5">
        <div id="myCarousel" class="carousel slide" data-ride="carousel" style="width:50%">
            <ol class="carousel-indicators">
                @for ($i = 0; $i < count($images); $i++)
                    <li data-target="#myCarousel" data-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}"></li>
                @endfor
            </ol>
            <div class="carousel-inner">
                @for ($i = 0; $i < count($images); $i++)
                    <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                        <svg class="bd-placeholder-img" width="100%" height="100%" preserveAspectRatio="xMidYMid slice"
                            role="img" focusable="false">
                            <rect width="100%" height="100%" fill="#777" />
                        </svg>
                        <img src="{{ asset('images') }}\{{ $images[$i] }}" />
                    </div>
                @endfor
            </div>
            <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <div class="container mx-auto mt-3">
        <p class="h3 text-black"> En Viveres Velasquez Silva (Vesil) encontraras variedad en Viveres, Medicinas, Alimentos,
            Bebidas.
            Ven a visitarnos en la Urb. La Rotaria Av. 84. La tiendita del Sr. Silva los espera atendidos por sus
            propietarios.
            El Sr. Silva y la Sra. Bella
        </p>
    </div>
@endsection

@push('js')
    <script>
        x`
    </script>
@endpush
