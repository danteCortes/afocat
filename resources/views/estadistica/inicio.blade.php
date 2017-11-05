@extends('plantillaadministrador')

@section('titulo')
CAT | Estadisticas
@stop

@section('metas')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('estilos')
{!!Html::style('bootstrap/css/bootstrap-datetimepicker.min.css')!!}
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
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
      <div class="alert alert-warning alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Cuidado!</strong> {{Session::get('advertencia')}}
      </div>
    @endif
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Mostrar estadísticas</h3>
      </div>
      <div class="panel-body">
          <div class="form-group">
            {!!Form::label('Inicio: ', null, ['class'=>'control-label'])!!}
            {!!Form::text('inicio', null, ['class'=>'form-control input-sm', 'id'=>'inicio'])!!}
          </div>
          <div class="form-group">
            {!!Form::label('Fin: ', null, ['class'=>'control-label'])!!}
            {!!Form::text('fin', null, ['class'=>'form-control input-sm', 'id'=>'fin'])!!}
          </div>
          {!!Form::button('Filtrar', ['class'=>'btn btn-primary btn-sm', 'type'=>'button', 'id'=>'filtrar'])!!}
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div id="estadisticas" class="col-xs-12">

  </div>
</div>
@stop

@section('scripts')
{!!Html::script('js/moment-with-locales.min.js')!!}
{!!Html::script('bootstrap/js/bootstrap-datetimepicker.min.js')!!}
<script>
  $(function(){

    $('#inicio').datetimepicker({
      locale: 'es',
      format: 'DD/MM/Y'
    });
    $('#fin').datetimepicker({
        useCurrent: false, //Important! See issue #1075
        locale: 'es',
        format: 'DD/MM/Y'
    });
    $("#inicio").on("dp.change", function (e) {
        $('#fin').data("DateTimePicker").minDate(e.date);
    });
    $("#fin").on("dp.change", function (e) {
        $('#inicio').data("DateTimePicker").maxDate(e.date);
    });

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $("#filtrar").click(function() {
      if (($("#inicio").val() != "") && ($("#fin").val() != "")) {

        $("#filtrar").prop('disabled', true);
        $("#estadisticas").html("<div class='progress'><div class='progress-bar progress-bar-striped active'"+
        " role='progressbar' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100' style='width: 100%'>"+
        "Consultando Base de Datos...</div></div>");

        $.post(
          "{{url('estadistica')}}",
          {
            inicio: $("#inicio").val(), fin: $("#fin").val()
          },
          function(data, textStatus, xhr) {
            $("#estadisticas").html(data);
            $("#filtrar").prop('disabled', false);
          });
      }
    });
  });
</script>
@stop
