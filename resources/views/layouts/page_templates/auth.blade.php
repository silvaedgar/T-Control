{{-- <div class="wrapper "> --}}
  @include('layouts.navbars.sidebar')
  <input type="hidden" id = "mensaje" value = "{{(session ('status') ?  session('status') :'') }}">
  <div class="main-panel">
    @include('layouts.navbars.navs.auth')
    @yield('content')
        {{-- @include('layouts.footers.auth') --}}
  </div>
{{-- </div> --}}
