<!DOCTYPE html>
<html lang="en">
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
    <link rel="stylesheet" href="/css/app.css">

</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">
        <nav class="main-header navbar navbar-expand cyane navbar-light border-bottom">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
              </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                     onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                                   <i class="nav-icon fas fa-power-off red"></i> <b>Salir</b>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                    </form>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
          <router-link to="dashboard" class="brand-link">
            <img src="{{ asset('/img/clocks.png') }}" alt="CRUD-LAR-VUE" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light"> <b>CONTROL-TED</b> </span>
          </router-link>

        <div class="sidebar">
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
              <img src="{{ asset('/img/useradmin.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info m-0">
              @can('isAdmin')
                <a href="#" class="d-block"> <b>{{ Auth::user()->name }}</b> <br><b>{{ Auth::user()->cargo->nombre }}</b></a>
              @endcan
              @can('isUser')
                <a href="#" class="d-block"> <b>Mentrax User</b> <br><b>Registrador</b></a>
              @endcan
            </div>
          </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @can('isAdmin')
          <li class="nav-item">
            <router-link to="dashboard" class="nav-link">
              <i> <img src="{{ asset('/img/monitor.png') }}" alt="" class="nav-icon"> </i>
              <p>
                Administración
              </p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="users" class="nav-link ">
              <i> <img src="{{ asset('/img/group.png') }}" alt="" class="nav-icon"> </i>
              <p>Usuarios</p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="personal" class="nav-link ">
              <i> <img src="{{ asset('/img/employee.png') }}" alt="" class="nav-icon"> </i>
              <p>Personal</p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="cargos" class="nav-link ">
              <i> <img src="{{ asset('/img/ballot.png') }}" alt="" class="nav-icon"> </i>
              <p>Cargos</p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="horario" class="nav-link ">
              <i> <img src="{{ asset('/img/alarm-clock.png') }}" alt="" class="nav-icon"> </i>
              <p>Horario</p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="scan" class="nav-link ">
              <i> <img src="{{ asset('/img/qr-menu.png') }}" alt="" class="nav-icon"> </i>
              <p>Scanner</p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="scaneo" class="nav-link ">
              <i> <img src="{{ asset('/img/qr-menu.png') }}" alt="" class="nav-icon"> </i>
              <p>Escaner dinámico</p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="registros" class="nav-link ">
              <i> <img src="{{ asset('/img/availability.png') }}" alt="" class="nav-icon"> </i>
              <p>Registros</p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="reportes" class="nav-link ">
              <i> <img src="{{ asset('/img/lista.png') }}" alt="" class="nav-icon"> </i>
              <p>Reportes</p>
            </router-link>
          </li>
          @endcan
          @can('isUser')
          <li class="nav-item">
            <router-link to="scan" class="nav-link ">
              <i> <img src="{{ asset('/img/qr-menu.png') }}" alt="" class="nav-icon"> </i>
              <p>Escaner normal</p>
            </router-link>
          </li>
          <li class="nav-item">
            <router-link to="scaneo" class="nav-link ">
              <i> <img src="{{ asset('/img/qr-menu.png') }}" alt="" class="nav-icon"> </i>
              <p>Escaner dinámico</p>
            </router-link>
          </li>
          @endcan
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                             <i class="nav-icon fas fa-power-off red"></i>
                <p> {{ __('Salir') }} </p>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

          <router-view></router-view>

          <vue-progress-bar></vue-progress-bar>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer cyane">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <b>TED - POTOSÍ</b>
    </div>
     <b>
    Created By &copy; Ing. Jorge Peralta </b>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

@auth
<script>
  window.user = @json(auth()->user())
</script>
@endauth

<!--<script src="/js/can.js"></script>-->
<script src="/js/app.js"></script>

</body>
</html>
