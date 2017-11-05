@extends('plantillanueva')

@section('titulo')
Vehiculos | Editar
@stop

@section('metas')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('estilos')
{!!Html::style('bootstrap/css/bootstrap-datetimepicker.min.css')!!}
@stop

@section('contenido')
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3">
		@if(Session::has('correcto'))
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong>Excelente!</strong> {{Session::get('correcto')}}
			</div>
		@elseif(Session::has('error'))
			<div class="alert alert-danger alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong>Excelente!</strong> {{Session::get('error')}}
			</div>
		@endif
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Editar Vehículo</h3>
			</div>
			<div class="panel-body">
				<p>Ingresar los datos del vehículo</p>
				{!!Form::open(['url'=>'vehiculo/'.$vehiculo->placa, 'class'=>'form-horizontal', 'method'=>'put'])!!}
					@if($vehiculo->persona)
					<div class="form-group">
						{!!Form::label('DNI/RUC:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
						<div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
							{!!Form::text('buscar_dni', $vehiculo->persona->dni, array('class'=>'form-control input-sm', 'placeholder'=>'DNI/RUC DEL CLIENTE', 'required'=>'', 'id'=>'buscar_dni'))!!}
						</div>
					</div>
					<div class="form-group">
						{!!Form::label('Cliente:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							{!!Form::text('cliente_encontrado', $vehiculo->persona->nombre, array('class'=>'form-control input-sm', 'placeholder'=>'CLIENTE', 'required'=>'', 'disabled'=>'', 'id'=>'cliente_encontrado'))!!}
						</div>
					</div>
					@elseif($vehiculo->empresa)
					<div class="form-group">
						{!!Form::label('DNI/RUC:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
						<div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
							{!!Form::text('buscar_dni', $vehiculo->empresa->ruc, array('class'=>'form-control input-sm', 'placeholder'=>'DNI/RUC DEL CLIENTE', 'required'=>'', 'id'=>'buscar_dni'))!!}
						</div>
					</div>
					<div class="form-group">
						{!!Form::label('Cliente:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							{!!Form::text('cliente_encontrado', $vehiculo->empresa->nombre, array('class'=>'form-control input-sm', 'placeholder'=>'CLIENTE', 'required'=>'', 'disabled'=>'', 'id'=>'cliente_encontrado'))!!}
						</div>
					</div>
					@endif
					<div class="form-group">
						{!!Form::label('Placa:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
						<div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
							{!!Form::text('placa', $vehiculo->placa, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Placa', 'required'=>''))!!}
						</div>
					</div>
					<div class="form-group">
						{!!Form::label('Marca:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
							{!!Form::text('marca', $vehiculo->marca, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Marca', 'required'=>''))!!}
						</div>
					</div>
					<div class="form-group">
						{!!Form::label('Modelo:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
							{!!Form::text('modelo', $vehiculo->modelo, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Modelo', 'required'=>''))!!}
						</div>
					</div>
					<div class="form-group">
            {!!Form::label('Color:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
              {!!Form::text('color', $vehiculo->color, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Color', 'required'=>''))!!}
            </div>
          </div>
					<div class="form-group">
						{!!Form::label('Clase:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
							{!!Form::text('clase', $vehiculo->clase, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Clase', 'required'=>''))!!}
						</div>
					</div>
					<div class="form-group">
						{!!Form::label('Categoría:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
							{!!Form::text('categoria', $vehiculo->categoria, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'CATEGORÍA', 'required'=>''))!!}
						</div>
					</div>
					<div class="form-group">
						{!!Form::label('Asientos:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
						<div class="col-xs-4 col-sm-4 col-md-3 col-lg-2">
							{!!Form::text('asientos', $vehiculo->asientos, array('class'=>'form-control input-sm', 'placeholder'=>'ASIENTOS', 'required'=>'', 'id'=>'asientos'))!!}
						</div>
					</div>
					<div class="form-group">
						{!!Form::label('Año de Fabricación:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
						<div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
							{!!Form::text('anio', $vehiculo->anio, array('class'=>'form-control input-sm', 'placeholder'=>'AÑO DE FABRICACIÓN', 'required'=>'', 'id'=>'anio'))!!}
						</div>
					</div>
					<div class="form-group">
						{!!Form::label('Nro. del motor:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
						<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
							{!!Form::text('motor', $vehiculo->motor, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Nro. del motor', 'required'=>''))!!}
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-8">
							{!!Form::submit('Guardar', ['class'=>'btn btn-primary btn-sm'])!!}
						</div>
					 </div>
				{!!Form::close()!!}
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
{!!Html::script('js/moment-with-locales.min.js')!!}
{!!Html::script('bootstrap/js/bootstrap-datetimepicker.min.js')!!}
{!!Html::script('js/jquery.mask.min.js')!!}
<script>
	$(function() {

		$("#anio").datetimepicker({
			viewMode: 'years',
			format: 'YYYY'
		})

		$("#asientos").mask('00');

		$("#buscar_dni").mask('00000000000');
		$("#buscar_dni").blur(function(){
			$("#cliente_encontrado").val("BUSCANDO CLIENTE...");
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.post(
				'<?=URL::to('vehiculo/buscar-dni')?>',
				{
					dni: $("#buscar_dni").val()
				},
				function(data, textStatus, xhr) {
					$("#cliente_encontrado").val(data);
				}
			);
		});
	});
</script>
@stop
