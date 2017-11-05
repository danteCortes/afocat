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

		<style>
		    input.transparent-input{
		       background-color:transparent !important;
		       border:none !important;
					 color: rgba(#7e8882, 0.91);
					 font-weight: bold;
					 font-family: sans-serif;
		    }
		</style>

    @yield('estilos')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src='https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js'></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body onload="scorrevole()">
    <!-- navbar fijo -->
    <nav class="navbar navbar-default navbar-fixed-top">
			<!-- <div class="form-group">
			  <input class="form-control transparent-input" type='text' name='name' id="banner">
			</div> -->
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
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="{{url('afiliacion')}}" class="navbar-brand">
          	AFILIACIÓN
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Afiliados <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ url('persona/create') }}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a></li>
                <li><a href="{{ url('persona') }}"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Ver Personas</a></li>
                <li><a href="{{ url('empresa') }}"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Ver Empresas</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Vehículos <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ url('vehiculo/create') }}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a></li>
                <li><a href="{{ url('vehiculo') }}"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Ver todos</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">CAT´S <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ url('afocat/create') }}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a></li>
                <li><a href="{{ url('afocat') }}"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Ver todos</a></li>
                <li>
                  <a href="{{ url('anulado/create') }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Anular CAT</a>
                </li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Duplicados <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ url('duplicado/create') }}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nuevo</a></li>
                <li><a href="{{ url('duplicado') }}"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Ver todos</a></li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Exportar Padrón <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ url('excel/create') }}"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Padrón Sunat</a></li>
                <li><a href="{{ url('padron-sbs') }}"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Padrón SBS</a></li>
                <li><a href="{{ url('padron-general') }}"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Padrón General</a></li>
                <li><a href="{{ url('padron-vendedores') }}"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Padrón Vendedores</a></li>
                <li><a href="{{ url('exportar-vencidos') }}"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Padrón Vencidos</a></li>
                <li><a href="{{ url('reporte-diario') }}"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Reporte Diario</a></li>
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
                <li><a href="{{url('siniestros')}}"> Siniestros</a></li>
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
		<script language="JavaScript"><!--
			var id,pause=0,position=0;
			function scorrevole() {
				var i,k,msg=" ---SISTEMA INTERNO DESARROLLADO POR SIPROM--- ";

				k=(100/msg.length)+1;
				for(i=0;i<=k;i++)
					msg+=" "+msg+msg;
				$("#banner").val(msg.substring(position,position+300));
				if(position++ == 100)
					position=0;
				id=setTimeout("scorrevole()",100);
			}//-->
		</script>
	</body>
</html>
