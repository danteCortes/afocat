@extends('plantillanueva')

@section('titulo')
CAT'S | Duplicados
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
        <h3 class="panel-title">AFOCAT's Duplicados</h3>
      </div>
      <div class="panel-body">
        <p>Tabla con todos los AFOCAT'S duplicados</p>
      </div>
      <table class="table table-bordered table-responsiveS" id="duplicados" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>NRO DUPLICADO</th>
            <th>DNI</th>
            <th>AFILIADO</th>
            <th>PLACA</th>
            <th>EMISIÓN</th>
            <th>VER</th>
            <th>EDITAR</th>
            <th>BORRAR</th>
          </tr>
        </thead>
        <tbody>
          @foreach($duplicados as $duplicado)
            <tr>
              <td>{{$duplicado->numero}}</td>
              <td>
                @if(isset($duplicado->afocat->vehiculo->persona))
                  {{$duplicado->afocat->vehiculo->persona->dni}}
                @elseif(isset($duplicado->afocat->vehiculo->empresa))
                  {{$duplicado->afocat->vehiculo->empresa->ruc}}
                @endif
              </td>
              <td>
                @if(isset($duplicado->afocat->vehiculo->persona))
                  {{$duplicado->afocat->vehiculo->persona->nombre}} {{$duplicado->afocat->vehiculo->persona->paterno}}
                   {{$duplicado->afocat->vehiculo->persona->materno}}
                @elseif(isset($duplicado->afocat->vehiculo->empresa))
                  {{$duplicado->afocat->vehiculo->empresa->nombre}}
                @endif
              </td>
              <td>{{$duplicado->afocat->vehiculo_placa}}</td>
              <td>{{$duplicado->emision}}</td>
              <td>
                <a href="<?=URL::to('duplicado/'.$duplicado->numero)?>" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Ver</a>
              </td>
              <td><a href="<?=URL::to('duplicado/'.$duplicado->numero.'/edit')?>" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Editar</a></td>
              <td>
                <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#borrar{{$duplicado->numero}}">
                  <span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Borrar
                </button>
                <div class="modal fade" id="borrar{{$duplicado->numero}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content" style="background-color: #FF3737">
                      <div class="modal-header">
                        <button class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Borrar Duplicado del CAT {{$duplicado->afocat_numero}}</h4>
                      </div>
                      <div class="modal-body">
                        {!!Form::open(['url'=>'duplicado/'.$duplicado->numero, 'class'=>'form-horizontal', 'method'=>'delete'])!!}
                        <p>Esta a punto de borrar el duplicado del CAT {{$duplicado->afocat_numero}}.</p>
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
@stop

@section('scripts')
{!!Html::script('js/datatables.min.js')!!}
{!!Html::script('bootstrap/js/dataTables.bootstrap.min.js')!!}
<script>
  $(function(){
    $("#duplicados").DataTable({
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
