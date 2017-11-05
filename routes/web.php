<?php

use Afocat\Usuario;
use Afocat\Persona;
use Afocat\Mail\CumpleaniosEmail;
use Afocat\Mail\RecordatorioMail;
use Afocat\Mail\BienvenidaEmail;

Route::get('subir-version', function(){
  set_time_limit(0);
  // actualiza la version del software.
  $cats = \Afocat\Afocat::all();
  foreach ($cats as $cat) {
    if ($persona = $cat->vehiculo->persona) {
      $cat->persona_dni = $persona->dni;
      $cat->save();
    }elseif ($empresa = $cat->vehiculo->empresa) {
      $cat->empresa_ruc = $empresa->ruc;
      $cat->save();
    }
    if ($duplicado = $cat->duplicado) {
      if ($persona = $cat->vehiculo->persona) {
        $duplicado->persona_dni = $persona->dni;
        $duplicado->save();
      }elseif ($empresa = $cat->vehiculo->empresa) {
        $duplicado->empresa_ruc = $empresa->ruc;
        $duplicado->save();
      }
    }
  }

  return "listo";
});

Route::get('actualizar', 'PadronController@actualizar');

// Rutas dee prueba y llenado de la Base de Datos
Route::get('enviar-sms', 'SmsController@enviarSms');

Route::get('padron/{nombre}', 'PadronController@subirPadron');

Route::get('subir-distritos', function(){
  Excel::load("public/ubigeo.xls", function($archivo){
    $fila = $archivo->get();
    foreach ($fila as $key => $value) {
      $distrito = new \Afocat\Distrito;
      $distrito->nombre = mb_strtoupper($value->distrito);
      $distrito->provincia_id = $value->provincia;
      $distrito->save();
    }
  })->get();
  return redirect('/');
});

Route::get('email', function(){
  Mail::to('dante.e.cortes@gmail.com')->send(new BienvenidaEmail('Dante Cortés'));
  return "email enviado";
});

// Ruta de inicio, si no hay ningun usuario administrador registrado muestra
// la vista de primera configuración del sistema, caso contrario muestra la
// vista para loguearse.
Route::get('/', function(){
  $usuario = Afocat\User::where('area', '0')->get()->first();
  if (isset($usuario)) {
    return redirect('/login');
  } else {
    return view('inicio.bienvenida');
  }
});

// Muestra el panel de inicio del administrador
Route::get('administrador', function(){
  return view('panel.paneladministrador');
});

// Muestra el panel de inicio del area de siniestros.
Route::get('siniestros', function(){
  return view('panel.panelsiniestros');
});

// Muestra el panel de inicio del área de afiliación.
Route::get('afiliacion', function () {
  return view('panel.panelafiliacion');
});

Route::get('afiliacion/filtrar-todos', 'AfocatController@filtrarTodos');

Route::resource('usuario', 'UsuarioController');

Route::resource('persona', 'PersonaController');

Route::post('persona/filtrar-todos', 'PersonaController@filtrarTodos');

Route::resource('empresa', 'EmpresaController');

Route::resource('vehiculo', 'VehiculoController');

// Filtra los vehiculos afiliados entre dos fechas.
Route::post('vehiculo/filtrar-todos', 'VehiculoController@filtrarTodos');

Route::resource('afocat', 'AfocatController');

Route::post('calcular-excedente', 'AfocatController@calcularExcedente');

// exporta los CAT's vencidos a un excel.
Route::get('exportar-vencidos', 'ExcelController@exportarCatsVencidos');

//muestra un formulario para descargar un excel SBS.
Route::get('padron-sbs', 'AfocatController@mostrarFormularioExportarSbs');

// Exporta los CAT's de un mes a un excel en formato de SBS.
Route::post('excel-interno', 'AfocatController@exportarExcelInterno');

//muestra un formulario para descargar un excel del Padrón general.
Route::get('padron-general', 'AfocatController@mostrarFormularioExportarGeneral');

// Exporta los CAT's de un mes a un excel en formato de Padrón General.
Route::post('excel-general', 'AfocatController@exportarExcelGeneral');

//muestra un formulario para descargar un excel del Padrón conn Vendedores.
Route::get('padron-vendedores', 'AfocatController@mostrarFormularioExportarVendedores');

// Exporta los CAT's de un mes a un excel en formato de Padrón General con Vendedores.
Route::post('excel-vendedores', 'AfocatController@exportarExcelVendedores');

Route::resource('anulado', 'AnuladoController');

Route::resource('duplicado', 'DuplicadoController');

Route::resource('excel', 'ExcelController');

Route::post('buscar-cat', 'DuplicadoController@buscarCat');

Route::post('vehiculo/buscar-dni', 'VehiculoController@otro');

Route::post('buscar-auto', 'VehiculoController@buscarAuto');

Route::resource('accidente', 'AccidenteController');

Route::post('buscar-distrito', 'AccidenteController@buscarDistrito');

Route::post('accidente/buscar-auto', 'AccidenteController@buscarAuto');

Route::resource('accidentado', 'AccidentadoController');

Route::post('accidentado/buscar-accidente', 'AccidentadoController@buscarAccidente');

Route::resource('gasto', 'GastoController');

Route::resource('pago', 'AccidentadoGastoController');

Route::post('pago/accidentado', 'AccidentadoGastoController@buscarAccidentado');

Route::get('siniestros/excel', 'AccidentadoGastoController@buscarMes');

Route::post('siniestros/excel', 'AccidentadoGastoController@descargarExcel');

Route::get('indemnizaciones', 'AccidentadoGastoController@indemnizaciones');

// Controlador que envia un email de felicitacion por cumpleaños a todos los que
// cumplen años en el día.
Route::get('felicitar-todos-email', 'EmailController@felicitarTodos');

// Controllador que envía un sms de felicitaciones por cumpleaños a todos los que
// cumplen años este día.
Route::get('felicitar-todos-sms', 'SmsController@felicitarTodos');

// Esta ruta muestra una vista con todos los que cumplen años en el día.
Route::get('cumpleanieros', 'EmailController@mostrarCumpleanieros');

// controlador para enviar email de felicitacion por cumpleaños a una persona.
Route::get('felicitar-cumpleanios-email/{dni}', 'EmailController@felicitarCumpleanios');

// controlador para enviar sms de felicitacion por cumpleaños a una persona.
Route::get('felicitar-cumpleanios-sms/{dni}', 'SmsController@felicitarCumpleanios');

// Método que envía un email recordatorio a todos los que tiene CAT´s por vencer
// en esta semana.
Route::get('recordar-todos-email', 'EmailController@recordarTodos');

// Método que envía un sms recordatorio a todos los que tiene CAT´s por vencer
// en esta semana.
Route::get('recordar-todos-sms', 'SmsController@recordarTodos');

// Muestra la vista con todos los afiliados que tienen CAT's que estan por vencer
// en la semana
Route::get('recordar', 'AfocatController@mostrarPorVencer');

// Enviar email recordatorio a una persona que se le va a vencer el CAT en esta semana.
Route::get('recordar-email/{dni}', 'EmailController@recordar');

// Enviar sms recordatorio a una persona que se le va a vencer el CAT en esta semana.
Route::get('recordar-sms/{dni}', 'SmsController@recordar');

// Método que envía un email recordatorio a todos los que tienen CAT´s vencidos.
Route::get('vencidos-todos-email', 'EmailController@recordarVencidos');

// Método que envía un sms recordatorio a todos los que tiene CAT´s vencidos.
Route::get('vencidos-todos-sms', 'SmsController@recordarVencidos');

// Muestra la vista con todos los afiliados que tienen CAT's que estan por vencer
// en la semana
Route::get('vencidos', 'AfocatController@mostrarVencidos');

// Enviar email recordatorio a una persona que se le va a vencer el CAT en esta semana.
Route::get('vencidos-email/{dni}', 'EmailController@vencidos');

// Enviar sms recordatorio a una persona que se le va a vencer el CAT en esta semana.
Route::get('vencidos-sms/{dni}', 'SmsController@vencidos');

// Muestra una vista con opciones para mostrar estadisticas entre dos fechas.
Route::get('estadisticas', 'EstadisticaController@inicio');

// Busca los resultados de la estadistica solicitada.
Route::post('estadistica', 'EstadisticaController@buscar');

// Mostrar formulario para buscar Vehiculo.
Route::get('consulta-sbs', 'AfocatController@mostrarFormularioBuscarVehiculo');

// Método para buscar un vehiculo.
Route::post('buscar-vehiculo-web', 'AfocatController@buscarVehiculo');

// muestra un formukario para subir un padron de afiliados.
Route::get('subir-padron', 'AfocatController@mostrarFormularioSubirPadron');

// sube un padron de afiliados al sistema
Route::post('subir-padron', 'AfocatController@subirPadron');

// Muestra un formulario para guardar un excel de siniestros.
Route::get('subir-siniestros', 'AccidentadoGastoController@mostrarFormularioSubirSiniestros');

// sube un padron de siniestros al sistema y a la BD
Route::post('subir-siniestros', 'AccidentadoGastoController@subirSiniestros');

Route::get('padron-siniestros/{nombre}', 'PadronController@subirSiniestros');

Auth::routes();

Route::get('/home', 'HomeController@index');

// Rutas para listar registros en las tablas bootstrip
Route::post('buscar-afiliado', 'AfiliadoController@buscarAfiliado');
Route::post('buscar-empresa', 'EmpresaController@buscarEmpresa');
Route::post('buscar-vehiculo', 'VehiculoController@buscarVehiculo');
Route::post('buscar-certificado', 'AfocatController@buscarCertificado');
Route::post('listar-accidentes', 'AccidenteController@listarAccidentes');

// Rutas para exportar padrones con registros entre un rango de fechas.
Route::post('exportar-sunat-rango', 'AfocatController@ExportarSunatRango');
Route::post('exportar-sbs-rango', 'AfocatController@ExportarSbsRango');
Route::post('exportar-general-rango', 'AfocatController@ExportarGeneralRango');
Route::post('exportar-vendedores-rango', 'AfocatController@ExportarVendedoresRango');

// Rutas para exportar reporte diario.
Route::get('reporte-diario', 'AfocatController@mostrarFormularioReporteDiario');
Route::post('exportar-diario', 'AfocatController@exportarReporteDiario');
