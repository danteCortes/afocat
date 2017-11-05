@extends('plantillanueva')

@section('titulo')
CAT's | Duplicado
@stop

@section('estilos')
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-sm-offset-2 col-md-offset-3 col-lg-offset-3">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">
          Datos del CAT Duplicado
        </h3>
      </div>
      <table class="table table-bordered table-responsive">
        <tr>
          <th>NUMERO</th>
          <td>{{$duplicado->afocat->numero}}</td>
        </tr>
        <tr>
          <th>EMISIÓN</th>
          <td>{{$duplicado->emision}}</td>
        </tr>
        <tr>
          <th>INICIO</th>
          <td>{{$duplicado->afocat->inicio_certificado}}</td>
        </tr>
        <tr>
          <th>FIN</th>
          <td>{{$duplicado->afocat->fin_contrato}}</td>
        </tr>
        <tr>
          <th>VEHÍCULO</th>
          <td>
            <a href="<?=URL::to('vehiculo/'.$duplicado->afocat->vehiculo->placa)?>" class="btn btn-warning btn-xs">
              {{$duplicado->afocat->vehiculo->placa}}
            </a>
          </td>
        </tr>
        <tr>
          <th>CLIENTE</th>
          <td>
          @if($duplicado->afocat->vehiculo->persona)
            <a href="<?=URL::to('persona/'.$duplicado->afocat->vehiculo->persona->dni)?>" class="btn btn-warning btn-xs">
              {{$duplicado->afocat->vehiculo->persona->nombre}}
            </a>
          @elseif($duplicado->afocat->vehiculo->empresa)
            <a href="<?=URL::to('empresa/'.$duplicado->afocat->vehiculo->empresa->ruc)?>" class="btn btn-warning btn-xs">
              {{$duplicado->afocat->vehiculo->empresa->nombre}}
            </a>
          @endif
          </td>
        </tr>
        <tr>
          <th>APORTE EXTRAORDINARIO</th>
          <td>S/ {{number_format($duplicado->monto, 2, '.', ' ')}}</td>
        </tr>
      </table>
      <div class="panel-footer">
        <a href="<?=URL::to('duplicado')?>" class="btn btn-primary">Atras</a>
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
@stop
