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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('favicon.ico') }}">

    <title>Subir Siniestros</title>

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
    <!-- Inicio de Contenido -->
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <div class="panel panel-default">
            <div class="panel-body">
              {!!Form::open(['url'=>'subir-siniestros', 'class'=>'form-inline', 'enctype'=>'multipart/form-data'])!!}
                <div class="form-group">
                  {!!Form::label('Padrón:', null, ['class'=>'control-label'])!!}
                  {!!Form::file('padron', null, ['class'=>'form-control input-sm', 'required'=>'', 'id'=>'padron'])!!}
                </div>
                {!!Form::button('Subir', ['class'=>'btn btn-primary btn-sm', 'type'=>'submit', 'id'=>'subir'])!!}
              {!!Form::close()!!}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Ubicado al final del documento para que la página cargue rápido -->
    {!!Html::script('js/jquery.min.js')!!}
    {!!Html::script('bootstrap/js/bootstrap.min.js')!!}


	</body>
</html>
