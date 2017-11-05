@extends('plantillanueva')

@section('titulo')
CAT | Anular
@stop

@section('metas')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('estilos')
{!!Html::style('bootstrap/css/dataTables.bootstrap.min.css')!!}
{!!Html::style('bootstrap/css/bootstrap-datetimepicker.min.css')!!}
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
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
        <h3 class="panel-title">Anular CAT</h3>
      </div>
      <div class="panel-body">
        <p>Ingresar los datos del CAT</p>
        {!!Form::open(['url'=>'anulado', 'class'=>'form-horizontal', 'method'=>'post'])!!}
          <div class="form-group">
            {!!Form::label('Nro. del Certificado:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
              {!!Form::text('numero', null, array('class'=>'form-control input-sm mayuscula', 'placeholder'=>'Nro. del Certificado', 'required'=>''))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Fecha:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
              {!!Form::text('fecha', null, array('class'=>'form-control input-sm', 'placeholder'=>'FECHA', 'required'=>'', 'id'=>'fecha'))!!}
            </div>
          </div>
          <div class="form-group">
            {!!Form::label('Denunciado:', null, array('class'=>'col-sm-4 hidden-xs control-label'))!!}
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
              <input type="checkbox" name="denuncia" value="1">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-4 col-sm-8">
              {!!Form::submit('Anular', ['class'=>'btn btn-primary btn-sm'])!!}
            </div>
           </div>
        {!!Form::close()!!}
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">CAT's Anulados</h3>
      </div>
      <div class="panel-body">
        <p>Tabla con todos los CAT'S Anulados</p>
      </div>
      <table class="table table-bordered table-responsiveS" id="anulados" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>NRO CAT</th>
            <th>FECHA</th>
            <th>BORRAR</th>
          </tr>
        </thead>
        <tbody>
          @foreach($anulados as $anulado)
            <tr>
              <td>{{$anulado->numero}}</td>
              <td>{{$anulado->fecha}}</td>
              <td>
                {!!Form::open(['url'=>'anulado/'.$anulado->id, 'method'=>'delete'])!!}
                  {!!Form::submit('Borrar', ['class'=>'btn btn-danger btn-xs'])!!}
                {!!Form::close()!!}
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@stop

@section('scripts')
{!!Html::script('js/moment-with-locales.min.js')!!}
{!!Html::script('bootstrap/js/bootstrap-datetimepicker.min.js')!!}
{!!Html::script('js/datatables.min.js')!!}
{!!Html::script('bootstrap/js/dataTables.bootstrap.min.js')!!}
<script>
  $(function(){
    $('#fecha').datetimepicker({
      locale: 'es',
      format: 'L'
    });

    $("#anulados").DataTable({
      "language":
        {
          "sProcessing":     "Procesando...",
          "sLengthMenu":     "Mostrar _MENU_ registros",
          "sZeroRecords":    "No se encontraron resultados",
          "sEmptyTable":     "Ningún dato disponible en esta tabla",
          "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
          "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
          "sInfoPostFix":    "",
          "sSearch":         "Buscar:",
          "sUrl":            "",
          "sInfoThousands":  ",",
          "sLoadingRecords": "Cargando...",
          "oPaginate": {
              "sFirst":    "Primero",
              "sLast":     "Último",
              "sNext":     "Siguiente",
              "sPrevious": "Anterior"
          },
          "oAria": {
              "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
              "sSortDescending": ": Activar para ordenar la columna de manera descendente"
          }
        }

    });
  });
</script>
@stop
