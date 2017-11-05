@extends('plantillasiniestros')

@section('titulo')
Accidentado | Mostrar
@stop

@section('estilos')
@stop

@section('contenido')
<div class="row">
	<div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-sm-offset-2 col-md-offset-3 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					Cliente {{$accidentado->nombre}}
				</h3>
			</div>
			<table class="table table-bordered table-condensed">
				<tr>
					<th>ACCIDENTE</th>
					<td>{{$accidentado->accidente->id}}</td>
				</tr>
				<tr>
					<th>CÓDIGO</th>
					<td>{{$accidentado->codigo}}</td>
				</tr>
				<tr>
					<th>NOMBRE Y APELLIDOS</th>
					<td>{{$accidentado->nombre}}</td>
				</tr>
				<tr>
					<th>DNI</th>
					<td>{{$accidentado->dni}}</td>
				</tr>
				<tr>
					<th>FORMA DE PAGO</th>
					<td>{{$accidentado->forma}}</td>
				</tr>
				<tr>
					<th>A 82</th>
					<td>{{number_format($accidentado->cuenta, 2, '.', ' ')}}</td>
				</tr>
				<tr>
					<th>ERA CUENTA A 82</th>
					<td>{{$accidentado->a_cuenta}}</td>
				</tr>
			</table>
			<div class="panel-footer">
				<a href="<?=URL::to('accidentado')?>" class="btn btn-primary">Atras</a>
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
@stop
