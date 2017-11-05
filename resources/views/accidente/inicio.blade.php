@extends('plantillasiniestros')

@section('titulo')
Accidente | Todos
@stop

@section('metas')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('estilos')
{!!Html::style('bootgrid/jquery.bootgrid.min.css')!!}
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
        <h3 class="panel-title">Accidentes</h3>
      </div>
      <div class="panel-body">
        <p>Tabla con todos los Accidentes</p>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-responsive" id="grid-data">
          <thead>
            <tr>
              <th data-column-id="codigo" data-order="asc" data-identifier="true">CÓDIGO</th>
              <th data-column-id="vehiculo">VEHÍCULO</th>
              <th data-column-id="ocurrencia">OCURRENCIA</th>
              <th data-column-id="commands" data-formatter="commands" data-sortable="false">VER</th>
            </tr>
          </thead>
        </table>
        <div class="modal fade" id="borrarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color: #FF3737">
              <div class="modal-header">
                <button class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Borrar Accidente <span class="codigo"></span> </h4>
              </div>
              <div class="modal-body">
                <p>Esta a punto de borrar el Accidente número <span class="codigo"></span>.</p>
                <p>Si esta seguro de esta decición continue en el botón Borrar de lo contrario pulse Cancelar</p>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="codigo" value="" id="codigo">
                <button class="btn btn-default" type="button" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="borrar">Borrar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
{!!Html::script('bootgrid/jquery.bootgrid.min.js')!!}
<script>
  $(function(){

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var grid = $("#grid-data").bootgrid({
      labels: {
        all: "todos",
        infos: "",
        loading: "Cargando datos...",
        noResults: "Ningun resultado encontrado",
        refresh: "Actualizar",
        search: "Buscar"
      },
      ajax: true,
      post: function () {
        /* To accumulate custom parameter with the request object */
        return {
          '_token': '{{ csrf_token() }}',
          id: "b0df282a-0d67-40e5-8558-c9e93b7befed"
        };
      },
      url: "{{url('/listar-accidentes')}}",
      formatters: {
        "commands": function (column, row) {
          return "<a class='btn btn-xs btn-info' href='accidente/"+row.codigo+"'> <i class='fa fa-eye' aria-hidden='true'></i> Ver</a> " +
            "<a class='btn btn-xs btn-warning' href='accidente/"+row.codigo+"/edit'> <i class='fa fa-pencil' aria-hidden='true'></i> Editar</a> " +
            "<button class='btn btn-danger btn-xs command-delete' data-codigo='"+row.codigo+"'>" +
              "<span class='glyphicon glyphicon-erase' aria-hidden='true'></span> Borrar" +
            "</button>";
        }
      }
    }).on("loaded.rs.jquery.bootgrid", function () {
      /* Executes after data is loaded and rendered */
      grid.find(".command-update").on("click", function (e) {


      }).end().find(".command-delete").on("click", function (e) {
          //le asignamos el id a la variable idC
          //Mostrar modal para editar ciudad
          $('#borrarModal').modal();
          //LLena los inputs del modal
          $('.codigo').html($(this).data("codigo"));
          $('#codigo').val($(this).data("codigo"));
      });
    });

    $("#borrar").click(function() {
      $.ajax({
        data: $("#codigo").val(),
        url: 'accidente/' + $("#codigo").val(),
        type: 'delete',
        beforeSend: function () {

        },
        success: function (data) {
          $("#borrarModal").modal('hide');
          $('#grid-data').bootgrid('reload');
        }
      });
    });


  });
</script>
@stop
