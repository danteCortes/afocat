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

    <title>Bienvenido</title>

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
            <h1>Bienvenido a la Familia de AFOCAT León de Huánuco<small> Siempre a su servicio</small></h1>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
          <img src="{{ url('imagenes/fondo.png') }}" alt="" class="img-responsive">
        </div>
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
          <p>SR(A): {{$nombre}}</p>
          <p>La familia de AFOCAT Regional León de Huánuco le da la bienvenida y agradece su confianza puesta en nosotros, por tal razón nos comprometemos en darle un servicio de primera calidad poniéndolo siempre en el primer lugar de nuestra agenda y apoyándolo en todo momento, aclarando sus dudas y preocupaciones.</p>
          <p>Sin más que agregar y resaltando nuestra alegria de formar parte de usted, nos despedimos con un gran abrazo.</p>
        </div>
      </div>
    </div> <!-- Fin de Contenido -->

    <!-- Inicio footer -->
    <footer class="footer">
      <div class="container">
        <p class="text-muted">Este mensaje fue enviado automáticamnte por <a href="{{ url('http://afocatregionalleondehuanuco.com') }}" target="_blank">afocat regional León de Huánuco</a>, no responda a este mensaje.</p>
      </div>
    </footer><!-- Fin del footer -->

    <!-- Ubicado al final del documento para que la página cargue rápido -->
    {!!Html::script('js/jquery.min.js')!!}
    {!!Html::script('bootstrap/js/bootstrap.min.js')!!}
  </body>
</html>
