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

    <link rel="icon" href="{{ url('favicon.ico') }}">

    <title>Feliz Cumpleaños</title>

    <!-- Bootstrap core CSS -->
    {!!Html::style('bootstrap/css/bootstrap.min.css')!!}
    <!-- Custom styles for this template -->
    {!!Html::style('css/afiliacion.css')!!}
    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src='https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js'></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-10 col-lg-8">
          <div class="page-header">
            <h1">Por que eres muy importante para nosotros<small> Siempre a su servicio</small></h1>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
          <img src="{{ url('imagenes/cumpleanios.jpg') }}" alt="" class="img-responsive img-rounded">
        </div>
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
          <p>SR(A): {{$persona->nombre}}</p>
          <p>Eres una persona muy especial y nos sentimos muy contentos por tener tu amistad y tu confianza. Que pases un cumpleaños muy hermoso y que tengas mucho éxito en este nuevo año de vida. Te lo desea, con mucho cariño, la familia de AFOCAT LEON DE HUÁNUCO.</p>
        </div>
      </div>
    </div> <!-- Fin de Contenido -->

    <!-- Inicio footer -->
    <footer class="footer">
      <div class="container">
        <p class="text-muted">Este mensaje fue enviado automáticamnte por <a href="{{ url('http://afocatregionalleondehuanuco.org') }}" target="_blank">afocat regional León de Huánuco</a>, no responda a este mensaje.</p>
      </div>
    </footer><!-- Fin del footer -->

    <!-- Ubicado al final del documento para que la página cargue rápido -->
    {!!Html::script('js/jquery.min.js')!!}
    {!!Html::script('bootstrap/js/bootstrap.min.js')!!}
  </body>
</html>