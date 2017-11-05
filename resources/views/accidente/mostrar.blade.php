@extends('plantillasiniestros')

@section('titulo')
Accidente | Mostrar
@stop

@section('estilos')
@stop

@section('contenido')
<div class="row">
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					Accidente {{$accidente->id}}
				</h3>
			</div>
      <div class="table-responsive">
        <table class="table table-bordered table-responsive">
  				<tr>
  					<th>CÓDIGO</th>
  					<td>{{$accidente->id}}</td>
  				</tr>
  				<tr>
  					<th>VEHÍCULO</th>
  					<td>{{$accidente->vehiculo->placa}}</td>
  				</tr>
  				<tr>
  					<th>ZONA GEOGRÁFICA</th>
  					<td>{{$accidente->provincia}} - {{$accidente->zona}}</td>
  				</tr>
  				<tr>
  					<th>FECHA DE OCURRENCIA</th>
  					<td>{{$accidente->ocurrencia}}</td>
  				</tr>
  				<tr>
  					<th>FECHA DE NOTIFICACIÓN</th>
  					<td>{{$accidente->notificacion}}</td>
  				</tr>
			  </table>
      </div>
			<div class="panel-footer">
				<a href="<?=URL::to('accidente')?>" class="btn btn-primary">Salir</a>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					Accidentados
				</h3>
			</div>
      <div class="table-responsive">
        <table id="accidentados" class="table table-bordered">
  				<thead>
  				  <tr>
  				    <th>CODIGO</th>
  				    <th>NOMBRE Y APELLIDOS</th>
  				  </tr>
  				</thead>
          <tbody>
            @foreach($accidente->accidentados as $accidentado)
            <tr>
              <td>{{$accidentado->codigo}}</td>
              <td>{{$accidentado->nombre}}</td>
            </tr>
            @endforeach
          </tbody>
			  </table>
      </div>
		</div>
	</div>
</div>
@stop

@section('scripts')
@stop
