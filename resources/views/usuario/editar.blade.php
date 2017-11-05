@extends('plantillaadministrador')

@section('titulo')
Editar Usuario
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Editar Usuario</h3>
      </div>
      <div class="panel-body">
        {{Form::open(['url'=>'usuario/'.$usuario->id, 'class'=>'form-horizontal', 'method'=>'put'])}}
          {{ csrf_field() }}
          <div class="form-group">
            {{Form::label('DNI*:', null, ['class'=>'control-label col-sm-3 col-md-4 col-lg-4'])}}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
              {{Form::text('dni', $usuario->persona_dni, ['class'=>'form-control', 'placeholder'=>'DNI', 'required'=>'', 'autofocus'=>''])}}
              @if ($errors->has('dni'))
                <span class="help-block">
                  <strong>{{ $errors->first('dni') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group">
            {{Form::label('Nombres y Apellidos*:', null, ['class'=>'control-label col-sm-3 col-md-4 col-lg-4'])}}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
              {{Form::text('nombre', $usuario->persona->nombre, ['class'=>'form-control mayuscula', 'placeholder'=>'NOMBRES Y APELLIDOS', 'required'=>''])}}
              @if ($errors->has('nombre'))
                <span class="help-block">
                  <strong>{{ $errors->first('nombre') }}</strong>
                </span>
              @endif
            </div>
          </div>
          <div class="form-group">
            {{Form::label('Teléfono*:', null, ['class'=>'control-label col-sm-3 col-md-4 col-lg-4'])}}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
              {{Form::text('telefono', $usuario->persona->telefono, ['class'=>'form-control mayuscula', 'placeholder'=>'TELÉFONO'])}}
            </div>
          </div>
          <div class="form-group">
            {{Form::label('Área*:', null, ['class'=>'control-label col-sm-3 col-md-4 col-lg-4'])}}
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
              <select class="form-control" name="area" required="">
                <option value="{{$usuario->area}}">
                  @if($usuario->area == 0)
                    ADMINISTRADOR
                  @elseif($usuario->area == 1)
                    AFILIACIONES
                  @elseif($usuario->area == 2)
                    SINIESTROS
                  @endif
                </option>
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
