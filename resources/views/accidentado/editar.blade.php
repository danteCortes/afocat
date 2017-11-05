@extends('plantillasiniestros')

@section('metas')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('titulo')
Editar Accidentado
@stop

@section('estilos')
{!!Html::style('bootstrap/css/bootstrap-datetimepicker.min.css')!!}
@stop

@section('contenido')
<?php $accidentes = \Afocat\Accidente::all(); ?>
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
    @if(count($errors) > 0)
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        @foreach($errors->all() as $error)
        <strong>Error!</strong> {{$error}}<br>
        @endforeach
      </div>
    @endif
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
        <h3 class="panel-title">Nuevo Accidentado</h3>
      </div>
      <div class="panel-body">
        <p>Ingresar los datos del Accidentado</p>
        {!!Form::open(['url'=>'accidentado/'.$accidentado->codigo, 'class'=>'form-horizontal', 'method'=>'put'])!!}
          <div class="form-group">
            {!!Form::label('Código del Accidente:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
              {!!Form::text('accidente_id', $accidentado->accidente->id, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'CÓDIGO DEL ACCIDENTE',
                'required'=>'', 'id'=>'accidente_id', 'autofocus'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Placa:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
              {!!Form::text('placa', null, array('class'=>'form-control input-sm', 'placeholder'=>'PLACA', 'required'=>'', 'id'=>'placa',
                'disabled'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('dueño:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
              {!!Form::text('duenio', null, array('class'=>'form-control input-sm', 'placeholder'=>'DUEÑO', 'required'=>'', 'id'=>'duenio',
                'disabled'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Vencimiento del Certificado:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
              {!!Form::text('vencimiento', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Vencimiento del Certificado',
                'id'=>'vencimiento', 'required'=>'', 'disabled'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('N° del CAT:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
              {!!Form::text('numero', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'N° DEL CAT',
                'id'=>'numero', 'required'=>'', 'disabled'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('DNI*:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-8 col-md-8 col-lg-8">
              {!!Form::text('dni', $accidentado->dni, array('class'=>'form-control input-sm dni', 'placeholder'=>'DNI', 'required'=>'', 'id'=>'dni'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Nombre y Apellidos:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-8 col-md-8 col-lg-8">
              {!!Form::text('nombre', $accidentado->nombre, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'NOMBRE Y APELLIDOS', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Forma de Pago:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-8 col-md-8 col-lg-8">
              {!!Form::text('forma_pago', $accidentado->forma, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'FORMA DE PAGO', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('A-82:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-8 col-md-8 col-lg-8">
              {!!Form::text('a_82', number_format($accidentado->cuenta, 2, '.', ' '), array('class'=>'form-control input-sm monto', 'placeholder'=>'A-82', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Cuenta A-82:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-8 col-md-8 col-lg-8">
              {!!Form::text('cuenta_a_82', $accidentado->a_cuenta, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'CUENTA A-82', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
              {!!Form::button('Guardar', ['class'=>'btn btn-primary btn-sm', 'name'=>'continuar', 'type'=>'submit'])!!}
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
  $(document).ready(function(){

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $(".monto").mask('# ##0.00', {reverse: true});

    $("#accidente_id").blur(function() {
      if ($(this).val() != "") {
        $("#placa").val('BUSCANDO DATOS...');
        $("#duenio").val('BUSCANDO DATOS...');
        $("#vencimiento").val('BUSCANDO DATOS...');
        $("#numero").val('BUSCANDO DATOS...');
        $.post("{{url('accidentado/buscar-accidente')}}",
        {id: $(this).val()},
        function(data, textStatus, xhr) {
          $("#placa").val(data['placa']);
          $("#duenio").val(data['duenio']);
          $("#vencimiento").val(data['vencimiento']);
          $("#numero").val(data['numero']);
        });
      }
    });

    $("#dni").mask("00000000");

    $("#accidente_id").blur();

  });
</script>
@stop
