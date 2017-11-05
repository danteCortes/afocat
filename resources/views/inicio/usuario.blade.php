@extends('inicio.plantilla')

@section('titulo')
Primer Usuario
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-lg-offset-2 col-md-offset-2">
    <div class="panel panel-default" style="background-color: rgb(255, 255, 128);">
      {!!Form::open(array('route'=>'usuario.store', 'method'=>'post', 'class'=>'form-horizontal'))!!}
        <div class="panel-heading" style="background-color: rgb(255, 255, 164); color: rgb(128, 0, 0);">
          <h4>Ingrese sus datos <small>de usuario</small></h4>
        </div>
        <div class="panel-body">
          <div class="form-group">
            {!!Form::label('DNI:', null, array('class'=>'col-sm-2 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6">
              {!!Form::text('dni', null, array('class'=>'form-control input-sm dni', 'placeholder'=>'DNI', 'required'=>''))!!}
              @if($errors->has('dni'))
              <p class="text-danger">{{$errors->first('dni')}}</p>
              @endif
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Nombres:', null, array('class'=>'col-sm-2 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6">
              {!!Form::text('nombre', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Nombres', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Apellido Paterno:', null, array('class'=>'col-sm-2 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6">
              {!!Form::text('paterno', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Apellido Paterno', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Apellido Materno:', null, array('class'=>'col-sm-2 hidden-xs control-label'))!!}
            <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6">
              {!!Form::text('materno', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Apellido Materno', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Teléfono:', null, array('class'=>'col-sm-2 hidden-xs control-label'))!!}
            <div class="col-xs-10 col-sm-10 col-md-8 col-lg-6">
              {!!Form::text('telefono', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Teléfono'))!!}
            </div>
          </div>
        </div>
        <div class="panel-footer" style="background-color: rgb(255, 255, 164);">
          {!!Form::submit('Guardar', array('class'=>'btn btn-default', 'style'=>'background-color: rgb(255, 43, 43); color: black;'))!!}
        </div>
      {!!Form::close()!!}
    </div>
  </div>
</div>
@stop

@section('script')
{!!Html::script('js/jquery.mask.min.js')!!}
<script>
  $(".dni").mask('00000000', {clearIfNotMatch: true});
</script>
@stop
