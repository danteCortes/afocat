@extends('plantillasiniestros')

@section('titulo')
Accidentados | Todos
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
        <h3 class="panel-title">Accidentados</h3>
      </div>
      <div class="panel-body">
        <p>Tabla con todos los Accidentados</p>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered" id="accidentes">
          <thead>
            <tr>
              <th>CÓDIGO</th>
              <th>NOMBRE Y APELLIDOS</th>
              <th>OCURRENCIA</th>
              <th>VER</th>
              <th>EDITAR</th>
              <th>BORRAR</th>
            </tr>
          </thead>
          <tbody>
            @foreach($accidentados as $accidentado)
              <tr>
                <td>{{$accidentado->codigo}}</td>
                <td>{{$accidentado->nombre}}</td>
                <td>{{$accidentado->accidente->ocurrencia}}</td>
                <td><a href="{{url('accidentado/'.$accidentado->codigo)}}" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-eye" aria-hidden="true"></span> Ver</a></td>
                <td><a href="{{url('accidentado/'.$accidentado->codigo.'/edit')}}" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar</a></td>
                <td>
                  <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#borrar{{str_replace('.', '_', $accidentado->codigo)}}">
                    <span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Borrar
                  </button>
                  <div class="modal fade" id="borrar{{str_replace('.', '_', $accidentado->codigo)}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content" style="background-color: #FF3737">
                        <div class="modal-header">
                          <button class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel">Eliminar Accidentado {{$accidentado->codigo}}</h4>
                        </div>
                        <div class="modal-body">
                          {!!Form::open(['url'=>'accidentado/'.$accidentado->codigo, 'class'=>'form-horizontal', 'method'=>'delete'])!!}
                          <p>Esta a punto de borrar el accidentado {{$accidentado->codigo}}. Esto implica que tambien se borrarán los gastos pagados
                            a este accidentado.</p>
                          <p>Si esta seguro de esta decición continue en el botón Borrar de lo contrario pulse Cancelar</p>
                        </div>
                        <div class="modal-footer">
                          <button class="btn btn-default" type="button" data-dismiss="modal">Cancelar</button>
                          <button type="submit" class="btn btn-primary">Borrar</button>
                          {!!Form::close()!!}
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
{!!Html::script('js/datatables.min.js')!!}
{!!Html::script('bootstrap/js/dataTables.bootstrap.min.js')!!}
<script>
  $(function(){
    $("#accidentes").DataTable({
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
