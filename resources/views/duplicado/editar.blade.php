@extends('plantillanueva')

@section('titulo')
CAT'S | Duplicado
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
        <strong>Error!</strong> {{Session::get('error')}}
      </div>
    @endif
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">AFOCAT Duplicado</h3>
      </div>
      <div class="panel-body">
        <p>Ingresar los datos del AFOCAT Duplicado</p>
        {!!Form::open(['url'=>'duplicado/'.$duplicado->numero, 'class'=>'form-horizontal', 'method'=>'put'])!!}
          <div class="form-group">
            {!!Form::label('PLACA:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
              {!!Form::text('buscar_cat', $duplicado->afocat->vehiculo_placa, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'CAT', 'required'=>'', 'id'=>'buscar_cat', 'disabled'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Fin Contrato:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
              {!!Form::text('cat_encontrado', $duplicado->afocat->fin_certificado, array('class'=>'form-control input-sm', 'placeholder'=>'FIN DE CONTRATO', 'required'=>'', 'id'=>'cat_encontrado', 'disabled'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Número:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
              {!!Form::text('numero', $duplicado->numero, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'NÚMERO', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Emisión:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
              {!!Form::text('emision', $duplicado->emision, array('class'=>'form-control input-sm', 'placeholder'=>'FECHA DE EMISIÓN', 'required'=>'', 'id'=>'emision'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Hora de Emisión:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
              {!!Form::text('hora', $duplicado->hora, array('class'=>'form-control input-sm', 'placeholder'=>'HORA DE EMISIÓN', 'required'=>'', 'id'=>'hora'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Monto S/:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
              {!!Form::text('monto', number_format($duplicado->monto, 2, '.', ' '), array('class'=>'form-control input-sm', 'placeholder'=>'S/ MONTO', 'required'=>'', 'id'=>'monto'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Total S/:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
              {!!Form::text('total', number_format($duplicado->monto+\Afocat\Afocat::where('vehiculo_placa', $duplicado->afocat->vehiculo_placa)
                ->orderBy('inicio_certificado', 'desc')->first()->extraordinario, 2, '.', ' '), array('class'=>'form-control input-sm',
                'placeholder'=>'S/ TOTAL', 'required'=>'', 'id'=>'total'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Actualización:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
              @if(\Afocat\Afocat::where('vehiculo_placa', $duplicado->afocat->vehiculo_placa)->orderBy('inicio_certificado', 'desc')->first()->acctualizacion)
                <input type="checkbox" name="actualizacion" value="1" class="checkbox" checked="">
              @else
                <input type="checkbox" name="actualizacion" value="1" class="checkbox">
              @endif
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
    $('#emision').datetimepicker({
      locale: 'es',
      format: 'L'
    });
    $("#hora").datetimepicker({
      format: 'LT'
    });

    $("#monto").mask('# ##0.00', {reverse: true});

    //buscar vehiculo
    $("#buscar_cat").blur(function(){
      $("#cat_encontrado").val("BUSCANDO CAT...");
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.post(
        "<?=URL::to('buscar-cat')?>",
        {
          numero: $(this).val()
        },
        function(data, textStatus, xhr) {
          $("#cat_encontrado").val(data['mensaje']);
        }
      );
    });//fin de buscar vehiculo
  });
</script>
@stop
