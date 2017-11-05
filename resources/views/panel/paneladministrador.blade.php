<?php
$porvencer = DB::table('afoc_afocats')->whereDate('fin_certificado', '<=', date('Y-m-d', strtotime('+1 week')))
  ->whereDate('fin_certificado', '>', date('Y-m-d'))->get();
$vencidos = DB::table('afoc_afocats')->whereDate('fin_certificado', '<=', date('Y-m-d'))
  ->whereDate('fin_certificado', '>', date('Y-m-d', strtotime('-1 month')))->get();
$cumpleanios = DB::table('afoc_personas')->whereDay('nacimiento', date('d'))->whereMonth('nacimiento', date('m'))->get();
 ?>
@extends('plantillaadministrador')

@section('titulo')
AFOCAT León de Huánuco
@stop

@section('estilos')
<style>
  .imagen {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    -webkit-transform: translate(-50%, -50%);
  }
</style>
<link rel="stylesheet" href="{{url('font-awesome/css/font-awesome.min.css')}}">
@stop

@section('contenido')
<div class="row">
  <div class="col-xs-12">
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
  </div>
  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">{{count($cumpleanios) - count(\Afocat\Mensaje::whereDate('fecha', date('Y-m-d'))->where('tipo', 1)->distinct()->get(['persona_dni']))}} Clientes de Cumpleaños <i class="fa fa-birthday-cake" aria-hidden="true"></i>
        <br>(Felicítalo ahora)</h3>
      </div>
      <div class="panel-body">
        <p><a href="{{url('felicitar-todos-email')}}" class="btn btn-danger">Enviar Email a Todos.</a></p>
        <p>
          <button class="btn btn-danger" data-toggle="modal" data-target="#felicitar">
             Enviar SMS a todos
          </button>
          <div class="modal fade" id="felicitar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content" style="background-color: #FF3737">
                <div class="modal-header" >
                  <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel" style="color: #FFFFFF">Felicitar a todos los cumpleañeros</h4>
                </div>
                <div class="modal-body" style="color: #FFFFFF">
                  <p>Esta a punto de enviar un sms a todos los que cumplen años hoy, esto reducirá sus sms disponibles de su cuenta.</p>
                  <p>Si esta seguro de esta decición continue en el botón Enviar de lo contrario pulse Cancelar</p>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-default" type="button" data-dismiss="modal">Cancelar</button>
                  <a href="{{url('felicitar-todos-sms')}}" class="btn btn-primary">Enviar.</a>
                </div>
              </div>
            </div>
          </div>
        </p>
      </div>
      <div class="panel-footer">
        <a href="{{url('cumpleanieros')}}" class="btn btn-primary">Felicitar uno por uno</a>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-body">
        <p><strong>Mensaje Predeterminado: </strong>"[AFILIADO] por ser tu cumpleaños te mandamos muchos abrazos y esperamos que cumplas muchos más.
          Te desea la familia de Afocat Regional León de Huánuco."</p>
          <p><strong>{{count(\Afocat\Mensaje::whereDate('fecha', date('Y-m-d', strtotime('-1 week')))->where('tipo', 1)->get())}}</strong>
            Mensajes de felicitaciones enviados en los últimos 7 días</p>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">{{count($porvencer) - count(\Afocat\Mensaje::whereDate('fecha', '>', date('Y-m-d'))->where('tipo', 2)->distinct()->get(['persona_dni']))}} CAT's vencen esta semana <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        <br>(Dentro de los siguientes 7 días)</h3>
      </div>
      <div class="panel-body">
        <p><a href="{{url('recordar-todos-email')}}" class="btn btn-danger">Enviar Email a Todos.</a></p>
        <p>
          <button class="btn btn-danger" data-toggle="modal" data-target="#recordar">
             Enviar SMS a los 50 primeros
          </button>
          <div class="modal fade" id="recordar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content" style="background-color: #FF3737">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel" style="color: #FFFFFF">Recordar vencimiento de CAT a todos</h4>
                </div>
                <div class="modal-body" style="color: #FFFFFF">
                  <p>Esta a punto de enviar un sms a los primeros 50 afiliados que se vencen su CAT en esta semana,
                    esto reducirá sus sms disponibles de su cuenta.</p>
                  <p>Si esta seguro de esta decición continue en el botón Enviar de lo contrario pulse Cancelar</p>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-default" type="button" data-dismiss="modal">Cancelar</button>
                  <a href="{{url('recordar-todos-sms')}}" class="btn btn-primary">Enviar</a>
                </div>
              </div>
            </div>
          </div>
        </p>
      </div>
      <div class="panel-footer">
        <a href="{{ url('recordar') }}" class="btn btn-primary">Recordar uno por uno</a>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-body">
        <p><strong>Mensaje Predeterminado: </strong>"SR(A) [AFILIADO] SU SOAT VENCERÁ EL [FECHA] Y UD FUE ELEGIDO, CON ESTE SMS PIDA SU DSCTO EN EL JR. CONSTITUCIÓN 938 HCO. ATTE AFOCAT REG LEON DE HUÁNUCO."</p>
          <p><strong>{{count(\Afocat\Mensaje::whereDate('fecha', '>', date('Y-m-d', strtotime('-1 week')))->where('tipo', 2)->get())}}</strong>
            Mensajes recordatorios enviados en los últimos 7 dias.</p>
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">{{count($vencidos) - count(\Afocat\Mensaje::whereDate('fecha', '>', date('Y-m-d'))->where('tipo', 3)->distinct()->get(['persona_dni']))}} CAT's vencieron hasta hoy <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        <br>(Histórico de 30 días)</h3>
      </div>
      <div class="panel-body">
        <p><a href="{{url('vencidos-todos-email')}}" class="btn btn-danger">Enviar Email a Todos.</a></p>
        <p>
          <button class="btn btn-danger" data-toggle="modal" data-target="#notificar">
             Enviar SMS a 200 primeros
          </button>
          <div class="modal fade" id="notificar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content" style="background-color: #FF3737; color: #FFFFFF">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">Notificar vencimiento de CAT a todos</h4>
                </div>
                <div class="modal-body">
                  <p >Esta a punto de enviar un sms a los 200 primeros afiliados que se vencieron su CAT en los últimos 30 días,
                    esto reducirá sus sms disponibles de su cuenta.</p>
                  <p>Si esta seguro de esta decición continue en el botón Enviar de lo contrario pulse Cancelar</p>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-default" type="button" data-dismiss="modal">Cancelar</button>
                  <a href="{{url('vencidos-todos-sms')}}" class="btn btn-primary">Enviar</a>
                </div>
              </div>
            </div>
          </div></p>
      </div>
      <div class="panel-footer">
        <a href="{{url('vencidos')}}" class="btn btn-primary">Recordar uno por uno</a>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-body">
        <p><strong>Mensaje Predeterminado: </strong>"SR(A) [AFILIADO] SU SOAT VENCIÓ EL [FECHA] Y UD FUE ELEGIDO, CON ESTE SMS PIDA SU DSCTO EN EL JR. CONSTITUCIÓN 938 HCO. ATTE AFOCAT REG LEON DE HUÁNUCO."</p>
          <p><strong>{{count(\Afocat\Mensaje::whereDate('fecha', '>', date('Y-m-d', strtotime('-1 week')))->where('tipo', 3)->get())}}</strong>
            Mensajes de notificación enviados en los últimos 7 días.</p>
      </div>
    </div>
  </div>
  <!--<div class="imagen">
    <img src="{{ url('imagenes/fondo.png') }}" alt="">
  </div>-->
</div>
<div class="row">
  <div class="col-xs-12">
    <div class="panel panel-default">
      <div class="panel-body">
          <p><strong>Mensajes enviados hasta el día de hoy: </strong>{{count(\Afocat\Mensaje::all())}}</p>
        </div>
      </div>
    </div>
  </div>
@stop

@section('script')

@stop
