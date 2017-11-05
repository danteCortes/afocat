@extends('plantillanueva')

@section('titulo')
CAT | Excel
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
        <h3 class="panel-title">Exportar a excel Padrón General con Vendedores</h3>
      </div>
      <div class="panel-body">
        <p>Ingresar mes y año</p>
        {!!Form::open(['url'=>'excel-vendedores', 'class'=>'form-horizontal'])!!}
        {{ csrf_field() }}
          <div class="form-group">
            {!!Form::label('Mes y Año:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
              {!!Form::text('mes_anio', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'MES Y AÑO', 'required'=>'', 'id'=>'mes_anio2'))!!}
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
              {!!Form::submit('Exportar', ['class'=>'btn btn-success btn-sm'])!!}
              {!!Form::button('Vista Rápida', ['class'=>'btn btn-primary btn-sm', 'type'=>'button', 'id'=>'calculo'])!!}
            </div>
           </div>
        {!!Form::close()!!}
        <div class="table-responsive" id="llenar">

        </div>
      </div>
    </div>
  </div>
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
        <h3 class="panel-title">Exportar a excel Padrón General con Vendedores</h3>
      </div>
      <div class="panel-body">
        <p>Ingresar rango de fechas</p>
        {!!Form::open(['url'=>'exportar-vendedores-rango', 'class'=>'form-inline'])!!}
        {{ csrf_field() }}
          <div class="form-group">
            {!!Form::label('Inicio:', null, array('class'=>'control-label'))!!}
            {!!Form::text('inicio', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'INICIO', 'required'=>'', 'id'=>'inicio'))!!}
          </div>
          <div class="form-group">
            {!!Form::label('Fin:', null, array('class'=>'control-label'))!!}
            {!!Form::text('fin', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'FIN', 'required'=>'', 'id'=>'fin'))!!}
          </div>
          <div class="form-group">
            {!!Form::submit('Exportar', ['class'=>'btn btn-success btn-sm'])!!}
           </div>
        {!!Form::close()!!}
        <div class="table-responsive" id="llenar">

        </div>
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
{!!Html::script('js/moment-with-locales.min.js')!!}
{!!Html::script('bootstrap/js/bootstrap-datetimepicker.min.js')!!}
<script>
  $(function(){
    $('#mes_anio').datetimepicker({
      locale: 'es',
      format: 'MM/YYYY'
    });

    $('#mes_anio2').datetimepicker({
      locale: 'es',
      format: 'MM/YYYY'
    });

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $("#sbs").click(function() {
      if ($("#mes_anio").val() != "") {
        $.post(
          "{{url('excel')}}",
          {
            mes_anio: $("#mes_anio").val()
          },
          function(data, textStatus, xhr) {

        });
      }
    });

    $("#calculo").click(function() {
      if ($("#mes_anio2").val() != "") {
        $.post(
          "{{url('calcular-excedente')}}",
          {
            mes_anio: $("#mes_anio2").val()
          },
          function(data, textStatus, xhr) {
            $("#llenar").html(data);
        });
      }
    });

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
  });
</script>
@stop
