@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@endsection




<nav class="navbar navbar-expand-lg navbar-absolute navbar-light bg-info fixed-top shadow">
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
</nav>

