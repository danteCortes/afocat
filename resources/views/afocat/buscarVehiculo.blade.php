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

    <title>Indemnizaciones</title>

    <!-- Bootstrap core CSS -->
    {!!Html::style('bootstrap/css/bootstrap.min.css')!!}

    <!-- Custom styles for this template -->
    {!!Html::style('css/afiliacion.css')!!}



    @yield('estilos')

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
              {!!Form::open(['url'=>'buscarVehiculo', 'class'=>'form-inline'])!!}
                <div class="form-group">
                  {!!Form::label('Placa:', null, ['class'=>'control-label'])!!}
                  {!!Form::text('placa', null, ['class'=>'form-control input-sm mayuscula', 'required'=>'', 'id'=>'placa'])!!}
                </div>
                {!!Form::button('Buscar', ['class'=>'btn btn-primary btn-sm', 'type'=>'button', 'id'=>'buscar'])!!}
              {!!Form::close()!!}
            </div>
            <div class="table-responsive" id="vehiculo">
              <table class="table table-bordered table-condensed">
                <thead>
                  <tr>
                    <th>PLACA</th>
                    <th>NRO. CAT</th>
                    <th>TIPO VEHICULO</th>
                    <th>INICIO CERTIFICADO</th>
                    <th>FIN CERTIFICADO</th>
                    <th>CONDICIÓN</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Ubicado al final del documento para que la página cargue rápido -->
    {!!Html::script('js/jquery.min.js')!!}
    {!!Html::script('bootstrap/js/bootstrap.min.js')!!}

  	<script type="text/javascript">
  	  $(function(){

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $("#buscar").click(function() {

          $.post(
            "{{url('buscar-vehiculo-web')}}",
            {
              placa: $("#placa").val()
            },
            function(data, textStatus, xhr) {
              $("#vehiculo").html(data);
          });
        });
      });
  	</script>
	</body>
</html>
