@extends('plantillanueva')

@section('titulo')
Afiliado | Editar
@stop

@section('estilos')
{!!Html::style('bootstrap/css/bootstrap-datetimepicker.min.css')!!}
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-sm-offset-2 col-md-offset-3 col-lg-offset-3">
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
        <strong>Error!</strong> {{Session::get('error')}}
      </div>
    @endif
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Editar Datos</h3>
      </div>
      <div class="panel-body">
        {!!Form::model($afiliado, ['route'=>['persona.update', $afiliado->dni], 'class'=>'form-horizontal', 'method'=>'put'])!!}
          <div class="form-group">
            {!!Form::label('DNI:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-7 col-md-6 col-lg-4">
              {!!Form::text('dni', null, array('class'=>'form-control input-sm', 'placeholder'=>'DNI', 'required'=>'', 'id'=>'dni'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Nombres:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
              {!!Form::text('nombre', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'NOMBRES', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Ap. Paterno:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
              {!!Form::text('paterno', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'APELLIDO PATERNO', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Materno:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
              {!!Form::text('materno', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'APELLIDO MATERNO', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Dirección:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
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
            {!!Form::label('Teléfono:', null, array('class'=>'col-sm-3 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-6">
              {!!Form::text('telefono', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Teléfono', 'required'=>''))!!}
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
  $("#dni").mask('00000000');
  $("#nacimiento").datetimepicker({
    locale: 'es',
    format: 'L'
  });

});
</script>
@stop
