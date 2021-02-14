<!doctype html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="Sistema bajo plataforma web para el control de  asistencia del personal del Tribunal Electoral de Potosí">
        <meta name="author" content="Ing. Jorge Peralta">
        <meta name="keyword" content="Sistema bajo plataforma web para el control de asistencia del personal del Tribunal Electoral de Potosí">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>TED - POTOSÍ</title>

        <link rel="shortcut icon" href="{{ asset('/img/control.png') }}" />

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Sura:wght@700&display=swap" rel="stylesheet">

        <link href="{{ asset('css/styleinit.css') }}" rel="stylesheet">

    </head>
    <body class="sidebar-dark-primary">
        <div  id="app">
            <div class="hero">
                <h1>CONTROL DE ASISTENCIA <br>
                    <a href="{{ route('login') }}" data-toggle="modal" data-target="#loginModal">
                        <img src="{{ asset('img/qrscan5.png') }}" width="150" class="my-4">
                    </a>
                    <br>
                    TED - POTOSÍ</h1>
                <div class="neon-light">
                </div>
                <main class="py-4">
                    @yield('content')
                </main>
                <div class="row">
                    <small class="footerleft">Created By  &copy;  Ing. Jorge Peralta </small>
                </div>
            </div>
        </div>
        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header cyane text-center">
                <h5 class="modal-title" id="exampleModalLongTitle"><b>{{ __('INICIAR SESIÓN') }}</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="background-color:#f4f6f9; ">
                 <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="cedula" class="col-md-5 col-form-label text-md-right">{{ __('Nro. Cédula:') }}</label>

                            <div class="col-md-7">
                                <input id="cedula" type="text" class="form-control{{ $errors->has('cedula') ? ' is-invalid' : '' }}" name="cedula" value="{{ old('cedula') }}" required autofocus>

                                @if ($errors->has('cedula'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('cedula') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-5 col-form-label text-md-right">{{ __('Contraseña:') }}</label>

                            <div class="col-md-7">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button type="button" class="btn sidebar-dark-primary text-white" data-dismiss="modal"><b>CANCELAR </b></button>
                            <button type="submit" class="btn cyane ">
                                <b>{{ __('INGRESAR') }}</b>
                            </button>
                        </div>
                    </form>
              </div>
            </div>
          </div>
        </div>
    </body>
    <script src="{{ asset('js/app.js') }}" ></script>
</html>
