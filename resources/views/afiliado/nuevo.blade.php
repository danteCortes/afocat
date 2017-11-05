@extends('plantillanueva')

@section('titulo')
Afiliados | Nuevo
@stop

@section('estilos')
{!!Html::style('bootstrap/css/bootstrap-datetimepicker.min.css')!!}
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Afiliado Nuevo</h3>
      </div>
      <div class="panel-body">
        <p>Ingresar los datos del afiliado</p>
        {!!Form::open(['url'=>'persona', 'class'=>'form-horizontal'])!!}
          <div class="form-group">
            {!!Form::label('DNI/RUC:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-7 col-md-6 col-lg-4">
              {!!Form::text('dni_ruc', null, array('class'=>'form-control input-sm', 'placeholder'=>'DNI/RUC', 'required'=>'', 'id'=>'dni_ruc'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Nombres*:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
              {!!Form::text('nombre', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Nombres', 'required'=>'', 'id'=>'nombre'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Ap. Paterno*:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
              {!!Form::text('paterno', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Apellido Paterno', 'required'=>'', 'id'=>'paterno'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Ap. Materno*:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
              {!!Form::text('materno', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Apellido Materno', 'required'=>'', 'id'=>'materno'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Dirección:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
              {!!Form::text('direccion', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Dirección', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Provincia:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
              {!!Form::text('provincia', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Provincia', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Representante:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
              {!!Form::text('representante', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'REPRESENTANTE', 'required'=>'', 'id'=>'representante', 'disabled'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Teléfono:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-6">
              {!!Form::text('telefono', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Teléfono', 'id'=>'telefono'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Email:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
              {!!Form::email('email', null, array('class'=>'form-control input-sm', 'placeholder'=>'EMAIL',))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Nacimiento:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
              {!!Form::text('nacimiento', null, array('class'=>'form-control input-sm', 'placeholder'=>'FECHA DE NACIMIENTO', 'id'=>'nacimiento'))!!}
            </div>
           </div>
           <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
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
$(function () {

  $("#dni_ruc").mask('00000000000');
  $("#dni_ruc").blur(function(){
    if ($(this).val() == "") {
      $("#representante").prop({
        disabled: true,
      });
      $("#nombre").prop('placeholder', 'NOMBRES');
    }else{
      if ($(this).val().length == 11) {
        $("#representante").prop({
          disabled: false,
        });
        $("#paterno").prop({
          disabled: true,
        });
        $("#materno").prop({
          disabled: true,
        });
        $("#nombre").prop('placeholder', 'RAZÓN SOCIAL');
      }else{
        $("#representante").prop({
          disabled: true,
        });
        $("#paterno").prop({
          disabled: false,
        });
        $("#materno").prop({
          disabled: false,
        });
        $("#nombre").prop('placeholder', 'NOMBRES');
        if ($(this).val().length != 8) {
          $(this).val("");
        }
      }
    }
  });

  $("#nacimiento").datetimepicker({
    locale: 'es',
    format: 'L'
  });

  $("#telefono").mask('000000000');

});
</script>
@stop
