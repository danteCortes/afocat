@extends('plantillanueva')

@section('titulo')
CAT | Editar
@stop

@section('metas')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('estilos')
{!!Html::style('bootstrap/css/bootstrap-datetimepicker.min.css')!!}
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3">
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
        <h3 class="panel-title">Editar CAT</h3>
      </div>
      <div class="panel-body">
        <p>Ingresar los datos del CAT</p>
        {!!Form::open(['url'=>'afocat/'.$afocat->numero, 'class'=>'form-horizontal', 'method'=>'put'])!!}
          <div class="form-group">
            {!!Form::label('Placa:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
              {!!Form::text('buscar_placa', $afocat->vehiculo->placa, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'PLACA', 'required'=>'', 'id'=>'buscar_placa'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Vehiculo:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
              {!!Form::text('vehiculo', $afocat->vehiculo->clase.' '.$afocat->vehiculo->marca.' '.$afocat->vehiculo->modelo.' '.$afocat->vehiculo->categoria, array('class'=>'form-control input-sm', 'placeholder'=>'VEHÍCULO', 'required'=>'', 'id'=>'vehiculo_encontrado', 'disabled'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('dueño:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
              @if($afocat->vehiculo->persona)
              {!!Form::text('duenio', $afocat->vehiculo->persona->nombre, array('class'=>'form-control input-sm', 'placeholder'=>'DUEÑO', 'required'=>'', 'id'=>'duenio_encontrado', 'disabled'=>''))!!}
              @elseif($afocat->vehiculo->empresa)
              {!!Form::text('duenio', $afocat->vehiculo->empresa->nombre, array('class'=>'form-control input-sm', 'placeholder'=>'DUEÑO', 'required'=>'', 'id'=>'duenio_encontrado', 'disabled'=>''))!!}
              @endif
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Nro. del Certificado:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
              {!!Form::text('numero', $afocat->numero, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Nro. del Certificado', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Inicio:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
              {!!Form::text('inicio_contrato', $afocat->inicio_contrato, array('class'=>'form-control input-sm', 'placeholder'=>'INICIO DEL CONTRATO', 'required'=>'', 'id'=>'inicio_contrato'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Hora de Emisión:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
              {!!Form::text('hora', $afocat->hora, array('class'=>'form-control input-sm', 'placeholder'=>'HORA DE EMISIÓN', 'required'=>'', 'id'=>'hora'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Monto SBS S/:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
              {!!Form::text('monto', number_format($afocat->monto, 2, '.', ' '), array('class'=>'form-control input-sm', 'placeholder'=>'S/ MONTO', 'required'=>'', 'id'=>'monto'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Total S/:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
              {!!Form::text('extraordinario', number_format($afocat->extraordinario+$afocat->monto, 2, '.', ' '), array('class'=>'form-control input-sm', 'placeholder'=>'S/ APORTE EXTRAORDINARIO', 'id'=>'extraordinario'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Vendedor:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
              {!!Form::text('vendedor', $afocat->vendedor, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'VENDEDOR'))!!}
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
{!!Html::script('js/jquery.mask.min.js')!!}
<script>
  $(function(){
    $('#inicio_contrato').datetimepicker({
      locale: 'es',
      format: 'L'
    });
    $("#hora").datetimepicker({
      format: 'LT'
    });

    $("#monto").mask('# ##0.00', {reverse: true});

    $("#extraordinario").mask('# ##0.00', {reverse: true});

    //buscar vehiculo
    $("#buscar_placa").blur(function(){
      $("#vehiculo_encontrado").val("BUSCANDO VEHÍCULO...");
      $("#duenio_encontrado").val("BUSCANDO DUEÑO...");
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.post(
        '<?=URL::to('buscar-auto')?>',
        {
          placa: $(this).val()
        },
        function(data, textStatus, xhr) {
          $("#vehiculo_encontrado").val(data['vehiculo']);
          $("#duenio_encontrado").val(data['duenio']);
        }
      );
    });//fin de buscar vehiculo
  });
</script>
@stop
