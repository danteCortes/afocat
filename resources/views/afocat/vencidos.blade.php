@extends('plantillaadministrador')

@section('titulo')
AFOCAT'S | CAT's vencidos
@stop

@section('estilos')
{!!Html::style('bootstrap/css/dataTables.bootstrap.min.css')!!}
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-8 col-lg-10 col-md-offset-2 col-lg-offset-1">
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
    @elseif(Session::has('advertencia'))
      <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Cuidado!</strong> {{Session::get('advertencia')}}
      </div>
    @endif
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">CAT's que se vencieron</h3>
      </div>
      <div class="panel-body">
        <p>Tabla con todos los CAT's que se vencieron en el último mes</p>
      </div>
      <table class="table table-bordered table-responsive" id="tabla" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>N° CAT</th>
            <th>PLACA</th>
            <th>AFILIADO</th>
            <th>EMAIL</th>
            <th>SMS</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cats as $cat)
            <tr>
              <td>{{$cat->numero}}</td>
              <td>{{$cat->vehiculo_placa}}</td>
              <td>
                @if($persona = $cat->vehiculo->persona)
                  {{$persona->nombre}}
                  @if(count(\Afocat\Mensaje::where('persona_dni', $persona->dni)->whereDate('fecha', '>', date('Y-m-d', strtotime('-1 week')))->where('tipo', 3)->get()))
                    (Mensaje enviado hace menos de 7 días)
                  @endif
                @elseif($empresa = $cat->vehiculo->empresa)
                  {{$empresa->nombre}}
                @endif
              </td>
              <td>
                @if(($persona = $cat->vehiculo->persona) != null)
                  @if($persona->email)
                    <a href="{{url('vencidos-email/'.$cat->numero)}}" class="btn btn-primary btn-xs">Email</a>
                  @endif
                @endif
              </td>
              <td>
                @if(($persona = $cat->vehiculo->persona) != null)
                  @if($persona->telefono)
                    <a href="{{url('vencidos-sms/'.$cat->numero)}}" class="btn btn-info btn-xs">SMS</a>
                  @endif
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <a href="{{url('administrador')}}" class="btn btn-primary">Regresar</a>
    <a href="{{url('exportar-vencidos')}}" class="btn btn-success">Exportar Excel</a>
  </div>
</div>
@stop

@section('scripts')
{!!Html::script('js/datatables.min.js')!!}
{!!Html::script('bootstrap/js/dataTables.bootstrap.min.js')!!}
<script>
  $(function(){

    $("#tabla").DataTable({
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
