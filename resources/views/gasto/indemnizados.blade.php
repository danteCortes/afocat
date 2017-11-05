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
          <table class="table table-bordered table-condensed">
            <thead>
              <tr>
                <th>DNI</th>
                <th>INDEMNIZADO</th>
                <th>N° CAT</th>
                <th>FECHA DEL ACCIDENTE</th>
                <th>LUGAR DEL ACCIDENTE</th>
                <th>N° PLACA</th>
                <th>FECHA LIMITE DEL COBRO</th>
              </tr>
            </thead>
            <tbody>
              @foreach($indemnizados as $indemnizado)
                <tr>
                  <td>{{$indemnizado->accidentado->dni}}</td>
                  <td>{{$indemnizado->accidentado->nombre}}</td>
                  <td>{{\Afocat\Afocat::where('vehiculo_placa', $indemnizado->accidentado->accidente->vehiculo->placa)
										->orderBy('inicio_certificado', 'desc')->first()->numero}}</td>
                  <td>{{$indemnizado->accidentado->accidente->ocurrencia}}</td>
                  <td>HUÁNUCO -
                    {{$indemnizado->accidentado->accidente->provincia}} -
                    {{$indemnizado->accidentado->accidente->zona}}
                  </td>
                  <td>{{$indemnizado->accidentado->accidente->vehiculo->placa}}</td>
                  <td>{{$indemnizado->fecha_limite}}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Ubicado al final del documento para que la página cargue rápido -->
    {!!Html::script('js/jquery.min.js')!!}
    {!!Html::script('bootstrap/js/bootstrap.min.js')!!}

  	@yield('scripts')
	</body>
</html>
