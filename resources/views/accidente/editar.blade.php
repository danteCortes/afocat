@extends('plantillasiniestros')

@section('metas')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('titulo')
Editar Accidente
@stop

@section('estilos')
{!!Html::style('bootstrap/css/bootstrap-datetimepicker.min.css')!!}
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">
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
        <h3 class="panel-title">Editar Accidente</h3>
      </div>
      <div class="panel-body">
        <p>Ingresar los datos del Accidente</p>
        {!!Form::open(['url'=>'accidente/'.$accidente->id, 'class'=>'form-horizontal', 'method'=>'put'])!!}
          <div class="form-group">
            {!!Form::label('Placa:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
              {!!Form::text('placa', $accidente->vehiculo_placa, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'PLACA',
              'required'=>'', 'id'=>'placa', 'autofocus'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Vehiculo:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
              {!!Form::text('vehiculo', null, array('class'=>'form-control input-sm', 'placeholder'=>'VEHÍCULO', 'required'=>'', 'id'=>'vehiculo',
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
            {!!Form::label('Provincia*:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-4">
              {!!Form::text('provincia', $accidente->provincia, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'PROVINCIA',
                'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Zona Geográfica*:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-4">
              {!!Form::text('zona', $accidente->zona, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'ZONA GEOGRÁFICA',
                'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Fecha de Ocurrencia:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
              {!!Form::text('ocurrencia', $accidente->ocurrencia, array('class'=>'form-control input-sm', 'placeholder'=>'OCURRENCIA', 'required'=>'', 'id'=>'ocurrencia'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Fecha de Notificación:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
              {!!Form::text('notificacion', $accidente->notificacion, array('class'=>'form-control input-sm', 'placeholder'=>'NOTIFICACIÓN', 'id'=>'notificacion'))!!}
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
<script>
  $(document).ready(function(){
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $("#provincia").change(function(){
      $.post("{{url('buscar-distrito')}}",
      {provincia_id: $(this).val()},
      function(data, textStatus, xhr) {
        var select = '<option value>--SELECCIONE OPCIÓN--</option>';
        var option = '';
        obj = data;
        $.each( obj, function( key, value ) {
            option = option + '<option value="'+value['id']+'">'+value['nombre']+'</option>';
        });
        select = select + option;
        $( "#distrito" ).html( select );
      });
    });
    $("#placa").blur(function(){
      $("#vehiculo").val('BUSCANDO DATOS...');
      $("#duenio").val('BUSCANDO DATOS...');
      $("#numero").val('BUSCANDO DATOS...');
      $("#vencimiento").val('BUSCANDO DATOS...');
      $.post("{{url('accidente/buscar-auto')}}",
      {auto_placa: $(this).val()},
      function(data, textStatus, xhr) {
        $("#vehiculo").val(data['vehiculo']);
        $("#duenio").val(data['duenio']);
        $("#numero").val(data['cat']);
        $("#vencimiento").val(data['vencimiento']);
      });
    });
    $('#ocurrencia').datetimepicker({
      locale: 'es',
      format: 'DD/MM/YYYY'
    });
    $('#notificacion').datetimepicker({
      locale: 'es',
      format: 'DD/MM/YYYY'
    });
    $("#placa").blur();
  });
</script>
@stop
