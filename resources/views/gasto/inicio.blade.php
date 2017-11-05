@extends('plantillasiniestros')

@section('titulo')
Tipos de Gasto
@stop

@section('estilos')

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
      <div class="alert alert-info alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Cuidado!</strong> {{Session::get('advertencia')}}
      </div>
    @endif
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Tipos de Gastos</h3>
      </div>
      <div class="table-responsive">
        <table id="gastos" class="table table-bordered">
          <thead>
            <tr>
              <th>TIPO</th>
              <th>BORRAR</th>
            </tr>
          </thead>
          <tbody>
            @foreach($gastos as $gasto)
            <tr>
              <td>{{$gasto->nombre}}</td>
              <td>
                <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#borrar{{$gasto->id}}">
                  <span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Borrar
                </button>
                <div class="modal fade" id="borrar{{$gasto->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content" style="background-color: #FF3737">
                      <div class="modal-header">
                        <button class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Borrar Tipo de Gasto {{$gasto->nombre}}</h4>
                      </div>
                      <div class="modal-body">
                        {!!Form::open(['url'=>'gasto/'.$gasto->id, 'class'=>'form-horizontal', 'method'=>'delete'])!!}
                        <p>Esta a punto de borrar el tipo de gasto {{$gasto->nombre}}, y con eso se eliminarán todos los gastos pagados a los accidentados
                          de este tipo de gasto.</p>
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
  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Nuevo Tipo Gasto</h3>
      </div>
      <div class="panel-body">
        {!!Form::open(['url'=>'gasto', 'class'=>'form-horizontal', 'method'=>'post'])!!}
          <div class="form-group">
            {!!Form::label('Nombre*:', null, ['class'=>'control-label col-sm-2'])!!}
            <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6">
              {{Form::text('nombre', null, ['class'=>'form-control input-sm mayuscula', 'placeholder'=>'NOMBRE', 'required'=>''])}}
            </div>
          </div>
          <div class="form-group">
            <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 col-sm-offset-2">
              {{Form::button('Guardar', ['class'=>'btn btn-primary btn-sm', 'type'=>'submit'])}}
            </div>
          </div>
        {!!Form::close()!!}
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')

@stop
