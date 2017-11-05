@extends('plantillasiniestros')

@section('metas')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('titulo')
Nuevo Pago
@stop

@section('estilos')
{!!Html::style('bootstrap/css/bootstrap-datetimepicker.min.css')!!}
@stop

@section('contenido')
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
      <strong>Error!</strong> {{Session::get('error')}}
    </div>
  @elseif(Session::has('advertencia'))
    <div class="alert alert-info alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>Cuidado!</strong> {{Session::get('advertencia')}}
    </div>
  @endif
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Registrar Pago a Accidentado</h3>
    </div>
    <div class="panel-body">
      {!!Form::open(['url'=>'pago', 'class'=>'form-horizontal', 'method'=>'post'])!!}
        <div class="form-group">
          {!!Form::label('Codigo Accidentado*:', null, ['class'=>'control-label col-sm-4'])!!}
          <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
            {!!Form::text('accidentado_codigo', null, ['class'=>'form-control input-sm', 'placeholder'=>'CODIGO DE ACCIDENTADO', 'required'=>'',
              'id'=>'accidentado_codigo'])!!}
          </div>
        </div>
        <div class="form-group">
          {!!Form::label('Accidentado:', null, ['class'=>'control-label col-sm-4'])!!}
          <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
            {!!Form::text('nombre', null, ['class'=>'form-control input-sm mayuscula', 'placeholder'=>'NOMBRES Y APELLIDOS', 'disabled'=>'', 'id'=>'nombre'])!!}
          </div>
        </div>
        <div class="form-group">
          {!!Form::label('Tipo de Gasto*:', null, ['class'=>'control-label col-sm-4'])!!}
          <div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
            <select class="form-control input-sm" name="gasto_id" required="">
              <option value=>SELECCIONE OPCCION</option>
              @foreach(\Afocat\Gasto::all() as $gasto)
              <option value="{{$gasto->id}}">{{$gasto->nombre}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
          {!!Form::label('Pagado:', null, ['class'=>'control-label col-sm-4'])!!}
          <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
            {!!Form::text('pagado', null, ['class'=>'form-control input-sm monto', 'placeholder'=>'BENEFICIOS PAGADOS', 'id'=>'nombre'])!!}
          </div>
        </div>
        <div class="form-group">
          {!!Form::label('Pendiente:', null, ['class'=>'control-label col-sm-4'])!!}
          <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
            {!!Form::text('pendiente', null, ['class'=>'form-control input-sm monto', 'placeholder'=>'PENDIENTE DE PAGO', 'id'=>'nombre'])!!}
          </div>
        </div>
        <div class="form-group">
          {!!Form::label('Fecha Límite de Pago:', null, ['class'=>'control-label col-sm-4'])!!}
          <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
            {!!Form::text('fecha_limite', null, ['class'=>'form-control input-sm', 'placeholder'=>'FECHA LIMITE DE PAGO',
              'id'=>'fecha_limite'])!!}
          </div>
        </div>
        <div class="form-group">
          {!!Form::label('Mostrar en Web:', null, ['class'=>'control-label col-sm-4'])!!}
          <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
            <input type="checkbox" name="estado" value="1" id="estado">
          </div>
        </div>
        <div class="form-group">
          <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 col-sm-offset-2">
            {{Form::button('Guardar', ['class'=>'btn btn-primary btn-sm', 'type'=>'submit'])}}
          </div>
        </div>
      {!!Form::close()!!}
    </div>
  </div>
</div>
@stop

@section('scripts')
{!!Html::script('js/jquery.mask.min.js')!!}
{!!Html::script('js/moment-with-locales.min.js')!!}
{!!Html::script('bootstrap/js/bootstrap-datetimepicker.min.js')!!}
<script>
  $(function(){
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $(".monto").mask('# ##0.00', {reverse: true});

    $("#accidentado_codigo").blur(function() {
      if ($(this).val() != "") {
        $.post("{{url('pago/accidentado')}}",
        {
          accidentado_codigo: $(this).val()
        },
        function(data, textStatus, xhr) {
          $("#nombre").val(data['nombre']);
        });
      }
    });

    $('#fecha_limite').datetimepicker({
      locale: 'es',
      format: 'DD/MM/YYYY'
    });
  });
</script>
@stop
