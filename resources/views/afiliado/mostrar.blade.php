@extends('plantillanueva')

@section('titulo')
Afiliado | Mostrar
@stop

@section('estilos')
@stop

@section('contenido')
<div class="row">
	<div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-sm-offset-2 col-md-offset-3 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					Cliente {{$afiliado->nombre}}
				</h3>
			</div>
			<table class="table table-bordered table-condensed">
				<tr>
					<th>NOMBRE Y APELLIDOS</th>
					<td>{{$afiliado->nombre}} {{$afiliado->paterno}} {{$afiliado->materno}}</td>
				</tr>
				<tr>
					<th>DNI</th>
					<td>{{$afiliado->dni}}</td>
				</tr>
				<tr>
					<th>DIRECCIÓN</th>
					<td>{{$afiliado->direccion}} {{$afiliado->provincia}} {{$afiliado->departamento}}</td>
				</tr>
				<tr>
					<th>TELEFONO</th>
					<td>{{$afiliado->telefono}}</td>
				</tr>
				<tr>
					<th>EMAIL</th>
					<td>{{$afiliado->email}}</td>
				</tr>
				<tr>
					<th>FECHA DE NACIMIENTO</th>
					<td>{{$afiliado->nacimiento}}</td>
				</tr>
			</table>
			<div class="panel-footer">
				<a href="<?=URL::to('persona')?>" class="btn btn-primary">Atras</a>
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
@stop
