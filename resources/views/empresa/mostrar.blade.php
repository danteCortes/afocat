@extends('plantillanueva')

@section('titulo')
Empresa | Mostrar
@stop

@section('estilos')

@stop

@section('contenido')
<div class="row">
	<div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-sm-offset-2 col-md-offset-3 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					Empresa {{$empresa->nombre}}
				</h3>
			</div>
			<table class="table table-bordered table-responsive">
				<tr>
					<th>NOMBRE</th>
					<td>{{$empresa->nombre}}</td>
				</tr>
				<tr>
					<th>RUC</th>
					<td>{{$empresa->ruc}}</td>
				</tr>
				<tr>
					<th>DIRECCIÓN</th>
					<td>{{$empresa->direccion}} {{$empresa->provincia}} {{$empresa->departamento}}</td>
				</tr>
				<tr>
					<th>REPRESENTANTE</th>
					<td>{{$empresa->representante}}</td>
				</tr>
				<tr>
					<th>TELEFONO</th>
					<td>{{$empresa->telefono}}</td>
				</tr>
				<tr>
					<th>EMAIL</th>
					<td>{{$empresa->email}}</td>
				</tr>
				<tr>
					<th>FECHA DE NACIMIENTO</th>
					<td>{{$empresa->nacimiento}}</td>
				</tr>
			</table>
			<div class="panel-footer">
				<a href="<?=URL::to('empresa')?>" class="btn btn-primary">Atras</a>
			</div>
		</div>
	</div>
</div>
@stop

@section('script')

@stop
