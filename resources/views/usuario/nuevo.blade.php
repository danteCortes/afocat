@extends('plantillaadministrador')

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Usuario Nuevo</h3>
      </div>
      <div class="panel-body">
        {{Form::open(['url'=>'usuario', 'class'=>'form-horizontal'])}}
          {{ csrf_field() }}
          <div class="form-group">
            {{Form::label('DNI*:', null, ['class'=>'control-label col-sm-3 col-md-4 col-lg-4'])}}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
              {{Form::text('dni', null, ['class'=>'form-control', 'placeholder'=>'DNI', 'required'=>'', 'autofocus'=>''])}}
              @if ($errors->has('dni'))
                <span class="help-block">
                  <strong>{{ $errors->first('dni') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group">
            {{Form::label('Nombres*:', null, ['class'=>'control-label col-sm-3 col-md-4 col-lg-4'])}}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
              {{Form::text('nombre', null, ['class'=>'form-control mayuscula', 'placeholder'=>'NOMBRES', 'required'=>''])}}
              @if ($errors->has('nombre'))
                <span class="help-block">
                  <strong>{{ $errors->first('nombre') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group">
            {{Form::label('Apellido Paterno*:', null, ['class'=>'control-label col-sm-3 col-md-4 col-lg-4'])}}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
              {{Form::text('paterno', null, ['class'=>'form-control mayuscula', 'placeholder'=>'APELLIDO PATERNO', 'required'=>''])}}
              @if ($errors->has('paterno'))
                <span class="help-block">
                  <strong>{{ $errors->first('paterno') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group">
            {{Form::label('Apellido Materno*:', null, ['class'=>'control-label col-sm-3 col-md-4 col-lg-4'])}}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
              {{Form::text('materno', null, ['class'=>'form-control mayuscula', 'placeholder'=>'APELLIDO MATERNO', 'required'=>''])}}
              @if ($errors->has('materno'))
                <span class="help-block">
                  <strong>{{ $errors->first('materno') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group">
            {{Form::label('Teléfono*:', null, ['class'=>'control-label col-sm-3 col-md-4 col-lg-4'])}}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
              {{Form::text('telefono', null, ['class'=>'form-control mayuscula', 'placeholder'=>'TELÉFONO'])}}
            </div>
          </div>
          <div class="form-group">
            {{Form::label('Área*:', null, ['class'=>'control-label col-sm-3 col-md-4 col-lg-4'])}}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
              <select class="form-control" name="area" required="">
                <option value>SELECCIONES UNA OPCION</option>
                <option value="0">ADMINISTRADOR</option>
                <option value="1">AFILIACIONES</option>
                <option value="2">SINIESTROS</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8 col-sm-offset-3 col-md-offset-4 col-lg-offset-4">
              {{Form::button('Guardar', ['class'=>'btn btn-primary', 'type'=>'submit'])}}
            </div>
          </div>
        {{Form::close()}}
      </div>
    </div>
  </div>
</div>
@stop
