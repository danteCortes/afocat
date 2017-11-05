@extends('plantillanueva')

@section('titulo')
CAT's | Mostrar
@stop

@section('estilos')
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-sm-offset-2 col-md-offset-3 col-lg-offset-3">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">
          Datos del CAT
        </h3>
      </div>
      <table class="table table-bordered table-responsive">
        <tr>
          <th>NUMERO</th>
          <td>{{$afocat->numero}}</td>
        </tr>
        <tr>
          <th>EMISIÓN</th>
          <td>{{$afocat->inicio_contrato}}</td>
        </tr>
        <tr>
          <th>INICIO</th>
          <td>{{$afocat->inicio_certificado}}</td>
        </tr>
        <tr>
          <th>FIN</th>
          <td>{{$afocat->fin_contrato}}</td>
        </tr>
        <tr>
          <th>VEHÍCULO</th>
          <td>
            <a href="<?=URL::to('vehiculo/'.$afocat->vehiculo->placa)?>" class="btn btn-warning btn-xs">
              {{$afocat->vehiculo->placa}}
            </a>
          </td>
        </tr>
        <tr>
          <th>CLIENTE</th>
          <td>
          @if($afocat->vehiculo->persona)
            <a href="<?=URL::to('persona/'.$afocat->vehiculo->persona->dni)?>" class="btn btn-warning btn-xs">
              {{$afocat->vehiculo->persona->nombre}}
            </a>
          @elseif($afocat->vehiculo->empresa)
            <a href="<?=URL::to('empresa/'.$afocat->vehiculo->empresa->ruc)?>" class="btn btn-warning btn-xs">
              {{$afocat->vehiculo->empresa->nombre}}
            </a>
          @endif
          </td>
        </tr>
        <tr>
          <th>VALOR</th>
          <td>S/ {{number_format($afocat->monto, 2, '.', ' ')}}</td>
        </tr>
        <tr>
          <th>APORTE DE RIESGO</th>
          <td>S/ {{number_format($afocat->monto*0.8, 2, '.', ' ')}}</td>
        </tr>
        <tr>
          <th>APORTE PARA GASTOS ADMINISTRATIVOS</th>
          <td>S/ {{number_format($afocat->monto*0.2, 2, '.', ' ')}}</td>
        </tr>
        <tr>
          <th>APORTE EXTRAORDINARIO</th>
          <td>S/ {{number_format($afocat->extraordinario, 2, '.', ' ')}}</td>
        </tr>
      </table>
      <div class="panel-footer">
        <a href="<?=URL::to('afocat')?>" class="btn btn-primary">Atras</a>
      </div>
    </div>
  </div>
</div>
@stop

@section('scripts')
@stop
