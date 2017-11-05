<!DOCTYPE html>
<html lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Plantilla elaborada para el cliente AFOCAT León de Huánuco">
    <meta name="author" content="Dante Esteban Cortés">
    @yield('metas')
    <link rel="icon" href="{{ url('favicon.ico') }}">

    <title>@yield('titulo')</title>

    <!-- Bootstrap core CSS -->
    {!!Html::style('bootstrap/css/bootstrap.min.css')!!}

    <!-- Custom styles for this template -->
    {!!Html::style('css/afiliacion.css')!!}

		<link rel="stylesheet" href="{{url('font-awesome/css/font-awesome.min.css')}}">

    @yield('estilos')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src='https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js'></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <!-- navbar fijo -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="/" class="navbar-brand">
          	{!!Html::image('imagenes/logo.png', 'León de Huánuco', array('height'=>'30', 'width'=>'30', 'class'=>'img-responsive'))!!}
          </a>
        </div>
				<div class="navbar-header">
          <a href="{{url('siniestros')}}" class="navbar-brand">
          	SINIESTROS
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Accidentes <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{url('accidente/create')}}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a></li>
                <li><a href="{{url('accidente')}}"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Buscar</a></li>
              </ul>
            </li>
          </ul>
					<ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Accidentados <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{url('accidentado/create')}}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a></li>
                <li><a href="{{url('accidentado')}}"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Buscar</a></li>
              </ul>
            </li>
          </ul>
					<ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gastos Pagados <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{url('pago/create')}}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a></li>
                <li><a href="{{url('pago')}}"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Buscar</a></li>
              </ul>
            </li>
          </ul>
					<ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Resumen <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{url('siniestros/excel')}}"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Exportar a excel</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{Auth::user()->persona->nombre}} <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Perfil</a></li>
                <li><a href="#">Cambiar Contraseña</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
									<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Salir</a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											{{ csrf_field() }}
									</form>
								</li>
              </ul>
            </li>
          </ul>
					@if(Auth::user()->area == 0)
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								 Áreas <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{url('administrador')}}"> Administrador</a></li>
                <li><a href="{{url('afiliacion')}}"> Afiliaciones</a></li>
              </ul>
            </li>
          </ul>
					@endif
        </div><!--/.nav-collapse -->
      </div>
    </nav><!-- Fin de navbar fijo -->

    <!-- Inicio de Contenido -->
    <div class="container">
			@yield('contenido')
    </div> <!-- Fin de Contenido -->

    <!-- Inicio footer -->
		<footer class="footer">
      <div class="container">
        <p class="text-muted">Copyright &copy; 2017 | Siprom</p>
      </div>
    </footer><!-- Fin del footer -->

    <!-- Ubicado al final del documento para que la página cargue rápido -->
    {!!Html::script('js/jquery.min.js')!!}
    {!!Html::script('bootstrap/js/bootstrap.min.js')!!}

  	@yield('scripts')
	</body>
</html>
