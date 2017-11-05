<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('titulo') | AFOCAT LEON DE HUÁNUCO</title>

    <!-- Bootstrap -->
    {!!Html::style('bootstrap/css/bootstrap.min.css')!!}
    <style>
      .mayuscula{
        text-transform: uppercase;
      }
    </style>
  </head>
  <body style="background-color: rgb(192, 192, 192); align-content: center;">
      <div class="page-header">
        <h2 style="text-align: center;">Sistema de Control Interno de AFOCAT Leon de Huánuco <small>Primeros Pasos</small></h2>
      </div>
    <div class="container-fluid">
    	@yield('contenido')
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    {!!Html::script('https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js')!!}
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    {!!Html::script('bootstrap/js/bootstrap.min.js')!!}

    @yield('script')
  </body>
</html>
