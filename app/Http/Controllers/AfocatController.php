<?php

namespace Afocat\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Afocat\Afocat;
use Afocat\Vehiculo;
use Afocat\Duplicado;
use Afocat\Anulado;
use Illuminate\Http\Request;
use Excel;
use Storage;
use Carbon\Carbon;

class AfocatController extends Controller{

  public function index(){

    return view('afocat.todos');
  }

  public function create(){
    return view('afocat.nuevo');
  }

  public function store(Request $request){

    if($duplicado = Duplicado::find($request->numero)){

      return redirect('duplicado')->with('error', 'EL NÚMERO DE CAT '.$request->numero.' INGRESADO YA ESTÁ EN USO COMO DUPLICADO,
        PUEDE BUSCARLO Y MODIFICARLO O BORRARLO E INGRESARLO NUEVAMENTE.');
    }elseif ($cat = Afocat::find($request->numero)) {

      return redirect('afocat')->with('error', 'EL NÚMERO DE CAT INGRESADO '.$request->numero.' YA ESTÁ EN USO, PUEDE BUSCARLO Y
        MODIFICARLO O BORRARLO E INGRESARLO NUEVAMENTE.');
    }elseif ($anulado = Anulado::where('numero', $request->numero)->first()) {

      return redirect('anulado/create')->with('error', 'EL NÚMERO DE CAT '.$request->numero.' INGRESADO ESTÁ ANULADO, PUEDE BUSCARLO Y
        BORRARLO E INGRESARLO NUEVAMENTE COMO CAT VENDIDO.');
    }

    return $this->guardarCat($request);
  }

  public function show(Afocat $afocat){
    return view('afocat.mostrar')->with('afocat', $afocat);
  }

  public function edit(Afocat $afocat){
    return view('afocat.editar', ['afocat'=>$afocat]);
  }

  public function update(Request $request, Afocat $afocat){
    $vehiculo = \Afocat\Vehiculo::find($request['buscar_placa']);
    $inicio = date('Y-m-d', strtotime(str_replace('/', '-', $request['inicio_contrato'])));
    $fin = date("Y-m-d", strtotime("1 year", strtotime($inicio)));
    if (isset($vehiculo)) {
      $afocat->numero = $request['numero'];
      $afocat->vehiculo_placa = mb_strtoupper($request['buscar_placa']);
      if ($vehiculo->persona_dni) {
        $afocat->persona_dni = $vehiculo->persona_dni;
      }elseif($vehiculo->empresa_ruc){
        $afocat->empresa_ruc = $vehiculo->empresa_ruc;
      }
      $afocat->inicio_contrato = $inicio;
      $afocat->fin_contrato = $fin;
      $afocat->inicio_certificado = $inicio;
      $afocat->fin_certificado = $fin;
      $afocat->hora = date('H:i:s', strtotime($request['hora']));
      $afocat->monto = $request['monto'];
      $afocat->extraordinario = $request['extraordinario']-$request['monto'];
      $afocat->vendedor = $request['vendedor'];
      $afocat->save();

      return redirect('afocat')->with('correcto', 'EL AFOCAT '.$afocat->numero.' SE ACTUALIZO EXITOSAMENTE.');
    } else {
      return redirect('afocat/'.$afocat->numero.'/edit')->with('error', 'LA PLACA INGRESADA NO ES CORRECTA, INTENTE NUEVAMENTE.');
    }
  }

  public function destroy(Afocat $afocat){
    $afocat->delete();
    return 1;
    return redirect('afocat')->with('advertencia', 'SE BORRO EL CAT '.$afocat->numero);
  }

  private function guardarCat(Request $request){

    if ($vehiculo = Vehiculo::find($request->buscar_placa)) {

      $afocat = new Afocat;
      $afocat->numero = $request['numero'];
      $afocat->vehiculo_placa = mb_strtoupper($request['buscar_placa']);
      if ($vehiculo->persona_dni) {
        $afocat->persona_dni = $vehiculo->persona_dni;
      }elseif($vehiculo->empresa_ruc){
        $afocat->empresa_ruc = $vehiculo->empresa_ruc;
      }
      $afocat->inicio_contrato = Carbon::createFromFormat('d/m/Y', $request->inicio_contrato)->format('Y-m-d');
      $afocat->fin_contrato = Carbon::createFromFormat('d/m/Y', $request->inicio_contrato)->addYear()->format('Y-m-d');
      $afocat->inicio_certificado = Carbon::createFromFormat('d/m/Y', $request->inicio_contrato)->format('Y-m-d');
      $afocat->fin_certificado = Carbon::createFromFormat('d/m/Y', $request->inicio_contrato)->addYear()->format('Y-m-d');
      $afocat->hora = date('H:i:s', strtotime($request['hora']));
      $afocat->monto = $request['monto'];
      $afocat->extraordinario = $request['extraordinario']-$request['monto'];
      $afocat->vendedor = mb_strtoupper($request['vendedor']);
      $afocat->registro = Carbon::now()->format('Y-m-d');
      $afocat->save();

      return redirect('afocat')->with('correcto', 'EL CERTIFICADO '.$request->numero.' FUE REGISTRADO CON EXITO.');;
    }else{

      return redirect('afocat/create')->with('error', 'LA PLACA '.$request->buscar_placa.' NO ESTA REGISTRADA EN LA BASE
        DE DATOS, REGISTRE EL VEHICULO PRIMERO.');;
    }
  }

  public function excel(){
    return view('afocat.excel.nuevo');

  }

  /**
   * Muestra todos los CAT's que se van a vencer dentro de siete días.
   */
  public function mostrarPorVencer(){
    $porvencer = Afocat::whereDate('fin_certificado', '<=', date('Y-m-d', strtotime('+1 week')))
      ->whereDate('fin_certificado', '>', date('Y-m-d'))->get();
    return view('afocat.porvencer')->with('cats', $porvencer);
  }

  /**
   * Muestra todos los CAT's que estan vencidos.
   */
  public function mostrarVencidos(){
    $vencidos = Afocat::whereDate('fin_certificado', '<=', date('Y-m-d'))
      ->whereDate('fin_certificado', '>', date('Y-m-d', strtotime('-1 month')))->get();
    return view('afocat.vencidos')->with('cats', $vencidos);
  }

  /**
   * Muestra una vista para buscar un vehiculo y su CAT.
   *
   * @return Response
   */
  public function mostrarFormularioBuscarVehiculo(){

    return view('afocat.buscarVehiculo');
  }

  /**
   * Busca un vehiculo
   *
   * @param String placa
   * @return String
   */
  public function buscarVehiculo(Request $request){

    $html = "<p>Este vehículo no existe</p>";
    $vehiculo = Vehiculo::find($request->placa);
    if($vehiculo){

      $cat = Afocat::where('vehiculo_placa', $vehiculo->placa)->orderBy('inicio_certificado', 'desc')->first();

      $html = "<table class='table table-bordered table-condensed'>
        <thead>
          <tr>
            <th>PLACA</th>
            <th>NRO. CAT</th>
            <th>TIPO VEHICULO</th>
            <th>INICIO CERTIFICADO</th>
            <th>FIN CERTIFICADO</th>
            <th>CONDICIÓN</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>".$vehiculo->placa."</td>
            <td>".$cat->numero."</td>
            <td>".$vehiculo->clase."</td>
            <td>".$cat->inicio_certificado."</td>
            <td>".$cat->fin_certificado."</td>
            <td>";
            if(strtotime(str_replace("/", "-", $cat->fin_contrato)) < strtotime(date('d-m-Y'))){
              $html .= "VENCIDO";
            }else{
              $html .= "ACTIVO";
            }
            $html .= "</td>
          </tr>
        </tbody>
      </table>";
    }
    return $html;
  }

  /**
   * Muestra un formulario para subir un padron de afiliados.
   *
   * @return Response
   */
  public function mostrarFormularioSubirPadron(){

    return view('afocat.formularioSubirPadron');
  }

  /**
   * sube un padron de afiliados en excel a la carpeta storage/app/public/afiliaciones
   * para luego actualizar la base de datos y subir sus datos de los afiliados,
   * CAT's, y Vehículos.
   *
   * @param Request
   * @return Response
   */
  public function subirPadron(Request $request){

    $padron = $request->file('padron');
    $nombre = time()."_".$padron->getClientOriginalName();
    Storage::disk('afiliados')->put($nombre, file_get_contents( $padron->getRealPath() ) );

    return redirect('padron/'.$nombre);
  }

  /**
   * Muestra un formulario para exportar un padron SBS a exce.
   *
   * @return Response
   */
  public function mostrarFormularioExportarSbs(){
    return view('afocat.excel.exportarSbs');
  }

  /**
   * Exporta los datos de los CAT's emitidos en un mes dado a un archivo excel para el control interno.
   * @param Reques
   * @return Excel
   */
  public function exportarExcelInterno(Request $request){

    set_time_limit(0);
    $mes = explode('/', $request->mes_anio)[0];
    $anio = explode('/', $request->mes_anio)[1];

    $anulados = \Afocat\Anulado::whereMonth('fecha', $mes)->whereYear('fecha', $anio)->get();
    $afocats = \Afocat\Afocat::whereMonth('inicio_certificado', $mes)->whereYear('inicio_certificado', $anio)->get();
    $datos = [];
    $nro_orden = 1;
    $total_monto = 0;
    $total_extra = 0;
    $mesAnio = $this->mesAnio($request);
    array_push($datos, ['NRO ORDEN', 'FECHA DE EMISION', 'FECHA DE INICIO', 'FECHA DE FIN', 'NRO. CAT', 'APELLIDOS Y NOMBRES',
      'DNI/RUC', 'PLACA DE RODAJE', 'ZONA GEOGRÁFICA', 'CATEGORIA DEL VEHICULO', 'USO VEH.', 'CLASE DE VEHICULO',
      'VALOR DEL CAT', 'APORTE DE RIESGO', 'APORTE PARA GASTO ADM', 'APORTE EXTRAORDINARIO', '']);

    foreach ($afocats as $afocat) {
      if ($afocat->persona) {
        $nro_documento = $afocat->persona->dni;
        $nombre = $afocat->persona->paterno." ".$afocat->persona->materno." ".$afocat->persona->nombre;
        $provincia = $afocat->persona->provincia;
      }else{
        $nro_documento = $afocat->empresa->ruc;
        $nombre = $afocat->empresa->nombre;
        $provincia = $afocat->empresa->provincia;
      }
      $estado = '';
      $extra = '';
      $monto = $afocat->monto;
      if ($afocat->inicio_contrato != $afocat->inicio_certificado) {
        if(Duplicado::where('numero', $afocat->numero)->first()){
          $estado = 'DUP '.Duplicado::where('numero', $afocat->numero)->first()->afocat->numero;
        }else{
          $estado = 'DUPLICADO';
        }
        $monto = '';
        $extra = $afocat->monto;
      }
      array_push($datos,
        [
          $nro_orden,
          $afocat->inicio_certificado,
          $afocat->inicio_contrato,
          $afocat->fin_contrato,
          $afocat->numero,
          $nombre,
          $nro_documento,
          $afocat->vehiculo->placa,
          $provincia,
          $afocat->vehiculo->categoria,
          $afocat->vehiculo->uso,
          $afocat->vehiculo->clase,
          $monto,
          $monto*0.8,
          $monto*0.2,
          $extra,
          $estado
        ]
      );
      $total_monto += $monto;
      $total_extra += $extra;
      $nro_orden++;
    }

    foreach ($anulados as $anulado) {
      array_push($datos, [
        $nro_orden,
        $anulado->fecha,
        '-',
        '-',
        $anulado->numero,
        '-',
        '00000000',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        'ANULADO'
      ]);
      $nro_orden++;
    }

    array_push($datos, ['', '', '', '', '', '', '', '', '', '', '', '', $total_monto, $total_monto*0.8, $total_monto*0.2, $total_extra]);

    array_push($datos, ['', '', '', '', '', '', '', '', '', '', '', '', '', '1%', '', '']);

    array_push($datos, ['', '', '', '', '', '', '', '', '', '', '', '', '', ($total_monto*0.8)*0.01, 'FONDO DE COMPENSACION', '']);

    Excel::create('PADRON '.$mesAnio['mes'].' '.$anio, function($excel) use ($datos, $mesAnio) {

      $excel->setCreator('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setCompany('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setLastModifiedBy('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setKeywords('AFOCAT REGIONAL LEÓN DE HUÁNUCO PADRON GENERAL SBS')
        ->setManager('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setDescription('Padrón general SBS');

      $excel->sheet('PADRON GENERAL '.$mesAnio['mes'].' SBS', function($sheet) use ($datos) {

        $sheet->setFontFamily('Cambria');
        $sheet->setFontSize(10);
        $sheet->setFontBold(true);

        $sheet->cells('A1:P1', function($cells){
          $cells->setBackground('#FFFF00');
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });

        $sheet->setColumnFormat(array(
          'A' => '0',
          'B' => 'dd/mm/yyyy',
          'C' => 'dd/mm/yyyy',
          'D' => 'dd/mm/yyyy',
          'E' => '@',
          'F' => '@',
          'G' => '@',
          'H' => '@',
          'I' => '@',
          'J' => '@',
          'K' => '@',
          'L' => '@',
          'M' => '0.00',
          'N' => '0.00',
          'O' => '0.00',
          'P' => '0.00',
          'Q' => '@',
        ));

        $sheet->setWidth(array('A'=>10, 'B'=>11, 'C'=>11, 'D'=>11, 'E'=>11, 'F'=>46, 'G'=>13, 'H'=>11,
          'I'=>15, 'J'=>11, 'K'=>13, 'L'=>15, 'M'=>11, 'N'=>11, 'O'=>11, 'P'=>11, 'Q'=>15));

        $sheet->setHeight(['1'=>55]);

        $sheet->setBorder('A1:Q'.count($datos));

        $sheet->fromArray($datos, null, 'A1', false, false);

      });

    })->export('xlsx');
  }

  /**
   * Muestra un formulario para exportar un padron General a excel.
   *
   * @return Response
   */
  public function mostrarFormularioExportarGeneral(){
    return view('afocat.excel.exportarGeneral');
  }

  /**
   * Exporta los datos de los CAT's emitidos en un mes dado a un archivo excel padrón general.
   * @param Request
   * @return Excel
   */
  public function exportarExcelGeneral(Request $request){

    set_time_limit(0);
    $mes = explode('/', $request->mes_anio)[0];
    $anio = explode('/', $request->mes_anio)[1];

    $anulados = \Afocat\Anulado::whereMonth('fecha', $mes)->whereYear('fecha', $anio)->get();
    $afocats = \Afocat\Afocat::whereMonth('inicio_certificado', $mes)->whereYear('inicio_certificado', $anio)->get();
    $datos = [];
    $total_monto = 0;
    $total_extra = 0;
    $nro_orden = 1;
    $mesAnio = $this->mesAnio($request);
    array_push($datos, ['NOR. ORDEN', 'DNI/RUC', 'MES', 'ESTADO', 'NRO. CAT', 'EMISION', 'INICIO', 'FIN',
      'NOMBRES Y APELLIDOS/RAZON SOCIAL', 'PLACA', 'PROVINCIA', 'CATEGORIA', 'USO', 'CLASE', 'PRECIO DE COSTO', 'MONTO EXTRAORDINARIO']);

    foreach ($afocats as $afocat) {
      if ($afocat->persona) {
        $nro_documento = $afocat->persona->dni;
        $nombre = $afocat->persona->paterno." ".$afocat->persona->materno." ".$afocat->persona->nombre;
        $provincia = $afocat->persona->provincia;
      }else{
        $nro_documento = $afocat->empresa->ruc;
        $nombre = $afocat->empresa->nombre;
        $provincia = $afocat->empresa->provincia;
      }
      $estado = '';
      $extra = '';
      $monto = $afocat->monto;
      if ($afocat->inicio_contrato != $afocat->inicio_certificado) {
        if(Duplicado::where('numero', $afocat->numero)->first()){
          $estado = 'DUP '.Duplicado::where('numero', $afocat->numero)->first()->afocat->numero;

        }else{
          $estado = 'DUPLICADO';
        }
        $monto = '';
        $extra = $afocat->monto;
      }
      array_push($datos,
        [
          $nro_orden,
          $nro_documento,
          $mesAnio['mes'],
          $estado,
          $afocat->numero,
          $afocat->inicio_certificado,
          $afocat->inicio_contrato,
          $afocat->fin_contrato,
          $nombre,
          $afocat->vehiculo->placa,
          $provincia,
          $afocat->vehiculo->categoria,
          $afocat->vehiculo->uso,
          $afocat->vehiculo->clase,
          $monto,
          $extra
        ]
      );
      $nro_orden++;
      $total_monto += $monto;
      $total_extra += $extra;
    }

    foreach ($anulados as $anulado) {
      array_push($datos, [
        $nro_orden,
        '00000000',
        $mesAnio['mes'],
        'ANULADO',
        $anulado->numero,
        $anulado->fecha,
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-'
      ]);
      $nro_orden++;
    }

    array_push($datos, [
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      'TOTAL',
      $total_monto,
      $total_extra
    ]);

    Excel::create('PADRON GRAL '.$mesAnio['mes'], function($excel) use ($datos, $mesAnio) {

      $excel->setCreator('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setCompany('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setLastModifiedBy('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setKeywords('AFOCAT REGIONAL LEÓN DE HUÁNUCO PADRON GENERAL SBS')
        ->setManager('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setDescription('Padrón general SBS');

      $excel->sheet('PADRON GENERAL '.$mesAnio['mes'], function($sheet) use ($datos) {

        $sheet->setFontFamily('Cambria');
        $sheet->setFontSize(10);
        $sheet->setFontBold(true);

        $sheet->cells('A1:P1', function($cells){
          $cells->setBackground('#9f9f9f');
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });

        $sheet->setColumnFormat(array(
          'A' => '0',
          'B' => '@',
          'C' => '@',
          'D' => '@',
          'E' => '@',
          'F' => 'dd/mm/yyyy',
          'G' => 'dd/mm/yyyy',
          'H' => 'dd/mm/yyyy',
          'I' => '@',
          'J' => '@',
          'K' => '@',
          'L' => '@',
          'M' => '@',
          'N' => '@',
          'O' => '0.00',
          'P' => '0.00',
        ));

        $sheet->setWidth(array('A'=>7, 'B'=>11, 'C'=>11, 'D'=>15, 'E'=>9, 'F'=>11, 'G'=>11, 'H'=>11,
          'I'=>40, 'J'=>11, 'K'=>13, 'L'=>10, 'M'=>11, 'N'=>15, 'O'=>11, 'P'=>11));

        $sheet->setHeight(['1'=>45]);

        $sheet->setBorder('A1:P'.count($datos));

        $sheet->fromArray($datos, null, 'A1', false, false);

      });

    })->export('xlsx');
  }

  /**
   * Muestra un formulario para exportar un padron General con vendedores a excel.
   *
   * @return Response
   */
  public function mostrarFormularioExportarVendedores(){
    return view('afocat.excel.exportarVendedores');
  }

  /**
   * Exporta los datos de los CAT's emitidos en un mes dado a un archivo excel padrón general.
   * @param Request
   * @return Excel
   */
  public function exportarExcelVendedores(Request $request){

    set_time_limit(0);
    $mes = explode('/', $request->mes_anio)[0];
    $anio = explode('/', $request->mes_anio)[1];

    $anulados = \Afocat\Anulado::whereMonth('fecha', $mes)->whereYear('fecha', $anio)->get();
    $afocats = \Afocat\Afocat::whereMonth('inicio_certificado', $mes)->whereYear('inicio_certificado', $anio)->get();
    $datos = [];
    $nro_orden = 1;
    $mesAnio = $this->mesAnio($request);
    array_push($datos, ['NOR. ORDEN', 'DNI/RUC', 'MES', 'ESTADO', 'NRO. CAT', 'EMISION', 'INICIO', 'FIN',
      'APELLIDOS Y NOMBRES/RAZON SOCIAL', 'PLACA', 'PROVINCIA', 'CATEGORIA', 'USO', 'CLASE', 'PRECIO DE COSTO SBS',
      'PRECIO DE VENTA', 'AFILIADOR']);
    $total_sbs = 0;
    $total_venta = 0;
    foreach($afocats as $afocat) {
      if ($afocat->persona) {
        $nro_documento = $afocat->persona->dni;
        $nombre = $afocat->persona->paterno." ".$afocat->persona->materno." ".$afocat->persona->nombre;
        $provincia = $afocat->persona->provincia;
      }else{
        $nro_documento = $afocat->empresa->ruc;
        $nombre = $afocat->empresa->nombre;
        $provincia = $afocat->empresa->provincia;
      }
      $estado = '';
      $monto = $afocat->monto;
      $vendedor = $afocat->vendedor;
      if ($afocat->inicio_contrato != $afocat->inicio_certificado) {
        if (Duplicado::where('numero', $afocat->numero)->first()) {
          # code...
          $estado = 'DUP '.Duplicado::where('numero', $afocat->numero)->first()->afocat->numero;

          if ($afocat->actualizacion) {
            # code...
            $vendedor = 'ACT '.Duplicado::where('numero', $afocat->numero)->first()->afocat->numero;
          }else{
            $vendedor = $estado;
          }
        }else{
          $estado = 'DUPLICADO';
          $vendedor = 'ACTUALIZACION';

        }
        $monto = '';
      }
      array_push($datos,
        [
          $nro_orden,
          $nro_documento,
          $mesAnio['mes'],
          $estado,
          $afocat->numero,
          $afocat->inicio_certificado,
          $afocat->inicio_contrato,
          $afocat->fin_contrato,
          $nombre,
          $afocat->vehiculo->placa,
          $provincia,
          $afocat->vehiculo->categoria,
          $afocat->vehiculo->uso,
          $afocat->vehiculo->clase,
          $monto,
          $afocat->monto + $afocat->extraordinario,
          $vendedor
        ]
      );
      $total_sbs += $monto;
      $total_venta += $afocat->monto + $afocat->extraordinario;
      $nro_orden++;
    }

    foreach ($anulados as $anulado) {
      $estado = 'ANULADO';
      if ($anulado->denuncia) {
        $estado = 'DENUNCIA';
      }
      array_push($datos, [
        $nro_orden,
        '00000000',
        $mesAnio['mes'],
        'ANULADO',
        $anulado->numero,
        $anulado->fecha,
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        $estado
      ]);
      $nro_orden++;
    }

    array_push($datos, [
      '', '', '', '', '', '', '', '', '', '', '', '', '', '', $total_sbs, $total_venta, ''
    ]);

    array_push($datos, [
      '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'EXCEDENTE', $total_venta-$total_sbs
    ]);

    Excel::create('PADRON GRAL '.$mesAnio['mes'], function($excel) use ($datos, $mesAnio) {

      $excel->setCreator('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setCompany('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setLastModifiedBy('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setKeywords('AFOCAT REGIONAL LEÓN DE HUÁNUCO PADRON GENERAL SBS')
        ->setManager('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setDescription('Padrón general SBS');

      $excel->sheet('PADRON GENERAL '.$mesAnio['mes'], function($sheet) use ($datos) {

        $sheet->setFontFamily('Cambria');
        $sheet->setFontSize(10);
        $sheet->setFontBold(true);

        $sheet->cells('A1:Q1', function($cells){
          $cells->setBackground('#BF0000');
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });

        $sheet->setColumnFormat(array(
          'A' => '0',
          'B' => '@',
          'C' => '@',
          'D' => '@',
          'E' => '@',
          'F' => 'dd/mm/yyyy',
          'G' => 'dd/mm/yyyy',
          'H' => 'dd/mm/yyyy',
          'I' => '@',
          'J' => '@',
          'K' => '@',
          'L' => '@',
          'M' => '@',
          'N' => '@',
          'O' => '0.00',
          'P' => '0.00',
          'Q' => '@',
        ));

        $sheet->setWidth(array('A'=>7, 'B'=>11, 'C'=>11, 'D'=>15, 'E'=>9, 'F'=>11, 'G'=>11, 'H'=>11,
          'I'=>40, 'J'=>11, 'K'=>13, 'L'=>10, 'M'=>11, 'N'=>15, 'O'=>11, 'P'=>11, 'Q'=>17));

        $sheet->setBorder('A1:Q'.count($datos));

        $sheet->setHeight(['1'=>45]);

        $sheet->fromArray($datos, null, 'A1', false, false);

      });

    })->export('xlsx');
  }

  public function calcularExcedente(Request $request){

    $mes = explode('/', $request->mes_anio)[0];
    $anio = explode('/', $request->mes_anio)[1];

    $afocats = \Afocat\Afocat::whereMonth('inicio_certificado', $mes)->whereYear('inicio_certificado', $anio)->get();
    $total_sbs = 0;
    $total_venta = 0;
    foreach ($afocats as $afocat) {

      $estado = '';
      $monto = $afocat->monto;
      $vendedor = $afocat->vendedor;
      if ($afocat->inicio_contrato != $afocat->inicio_certificado) {

        $monto = '';
      }
      $total_sbs += $monto;
      $total_venta += $afocat->monto + $afocat->extraordinario;
    }
    return "<table class='table table-condensed'>
      <tr>
        <th>TOTAL SBS</th>
        <th>TOTAL VENTA</th>
        <th>TOTAL EXCEDENTE</th>
      </tr>
      <tr>
        <td>".number_format($total_sbs, 2, '.', ' ')."</td>
        <td>".number_format($total_venta, 2, '.', ' ')."</td>
        <td>".number_format($total_venta-$total_sbs, 2, '.', ' ')."</td>
      </tr>
    </table>";
  }

  public function mesAnio(Request $request){

    $mes = explode('/', $request->mes_anio)[0];
    $anio = explode('/', $request->mes_anio)[1];

    switch ($mes) {
      case '01':
        $mes = 'ENERO';
        break;
      case '02':
        $mes = 'FEBRERO';
        break;
      case '03':
        $mes = 'MARZO';
        break;
      case '01':
        $mes = 'ENERO';
        break;
      case '04':
        $mes = 'ABRIL';
        break;
      case '05':
        $mes = 'MAYO';
        break;
      case '06':
        $mes = 'JUNIO';
        break;
      case '07':
        $mes = 'JULIO';
        break;
      case '08':
        $mes = 'AGOSTO';
        break;
      case '09':
        $mes = 'SETIEMBRE';
        break;
      case '10':
        $mes = 'OCTUBRE';
        break;
      case '11':
        $mes = 'NOVIEMBRE';
        break;
      case '12':
        $mes = 'DICIEMBRE';
        break;

      default:
        break;
    }

    return ['mes'=>$mes, 'anio'=>$anio];
  }

  public function mesLetras($mes){

    switch ($mes) {
      case '01':
        return 'ENERO';
        break;
      case '02':
        return 'FEBRERO';
        break;
      case '03':
        return 'MARZO';
        break;
      case '01':
        return 'ENERO';
        break;
      case '04':
        return 'ABRIL';
        break;
      case '05':
        return 'MAYO';
        break;
      case '06':
        return 'JUNIO';
        break;
      case '07':
        return 'JULIO';
        break;
      case '08':
        return 'AGOSTO';
        break;
      case '09':
        return 'SETIEMBRE';
        break;
      case '10':
        return 'OCTUBRE';
        break;
      case '11':
        return 'NOVIEMBRE';
        break;
      case '12':
        return 'DICIEMBRE';
        break;

      default:
        break;
    }

  }

  public function buscarCertificado(Request $request){

      $line_quantity = intVal($request->current);
      $line_number = intVal($request->rowCount);
      $where = $request->searchPhrase;
      $sort = $request->sort;

      if (isset($sort['numero'])) {
          $order_by = 'numero';
          $order_name = $sort['numero'];
      }
      if (isset($sort['dni'])) {
          $order_by = 'documento';
          $order_name = $sort['dni'];
      }
      if (isset($sort['afiliado'])) {
          $order_by = 'afiliado';
          $order_name = $sort['afiliado'];
      }
      if (isset($sort['placa'])) {
          $order_by = 'placa';
          $order_name = $sort['placa'];
      }
      if (isset($sort['fin'])) {
          $order_by = 'fin';
          $order_name = $sort['fin'];
      }

      $skip = 0;
      $take = $line_number;

      if ($line_quantity > 1) {
          //DESDE QUE REGISTRO SE INICIA
          $skip = $line_number * ($line_quantity - 1);
          //CANTIDAD DE RANGO
          $take = $line_number;
      }
      //Grupo de datos que enviaremos al modelo para filtrar
      if ($request->rowCount < 0) {
      } else {

        if (empty($where)) {
          $data_list_1 = DB::table('afoc_afocats')
            ->join('afoc_vehiculos', 'afoc_afocats.vehiculo_placa', '=', 'afoc_vehiculos.placa')
            ->join('afoc_personas', 'afoc_afocats.persona_dni', '=', 'afoc_personas.dni')
            ->select(
              'afoc_afocats.numero as numero',
              'afoc_personas.dni as documento',
              DB::raw("concat(afoc_personas.nombre, ' ', afoc_personas.paterno, ' ', afoc_personas.materno) as afiliado"),
              'afoc_afocats.vehiculo_placa as placa',
              'afoc_afocats.fin_certificado as fin'
            );

          $data_list_2 = DB::table('afoc_afocats')
            ->join('afoc_vehiculos', 'afoc_afocats.vehiculo_placa', '=', 'afoc_vehiculos.placa')
            ->join('afoc_empresas', 'afoc_vehiculos.empresa_ruc', '=', 'afoc_empresas.ruc')
            ->select(
              'afoc_afocats.numero as numero',
              'afoc_empresas.ruc as documento',
              'afoc_empresas.nombre as afiliado',
              'afoc_afocats.vehiculo_placa as placa',
              'afoc_afocats.fin_certificado as fin'
            );

          $data_list = $data_list_2->union($data_list_1)
          ->offset($skip)
          ->limit($take)
          ->orderBy($order_by, $order_name)
          ->get();

        } else {
          $data_list_1 = DB::table('afoc_afocats')
            ->join('afoc_vehiculos', 'afoc_afocats.vehiculo_placa', '=', 'afoc_vehiculos.placa')
            ->join('afoc_personas', 'afoc_afocats.persona_dni', '=', 'afoc_personas.dni')
            ->select(
              'afoc_afocats.numero as numero',
              'afoc_personas.dni as documento',
              DB::raw("concat(afoc_personas.nombre, ' ', afoc_personas.paterno, ' ', afoc_personas.materno) as afiliado"),
              'afoc_afocats.vehiculo_placa as placa',
              'afoc_afocats.fin_certificado as fin'
            )
            ->where('numero', 'like', '%' . $where . '%')
            ->orWhere('dni', 'like', '%' . $where . '%')
            ->orWhere(DB::raw("concat(afoc_personas.nombre, ' ', afoc_personas.paterno, ' ', afoc_personas.materno)"), 'like', '%' . $where . '%')
            ->orWhere('placa', 'like', '%' . $where . '%')
            ->orWhere('fin_certificado', 'like', '%' . $where . '%');

          $data_list_2 = DB::table('afoc_afocats')
            ->join('afoc_vehiculos', 'afoc_afocats.vehiculo_placa', '=', 'afoc_vehiculos.placa')
            ->join('afoc_empresas', 'afoc_vehiculos.empresa_ruc', '=', 'afoc_empresas.ruc')
            ->select(
              'afoc_afocats.numero as numero',
              'afoc_empresas.ruc as documento',
              'afoc_empresas.nombre as afiliado',
              'afoc_afocats.vehiculo_placa as placa',
              'afoc_afocats.fin_certificado as fin'
            )
            ->where('numero', 'like', '%' . $where . '%')
            ->orWhere('ruc', 'like', '%' . $where . '%')
            ->orWhere('nombre', 'like', '%' . $where . '%')
            ->orWhere('placa', 'like', '%' . $where . '%')
            ->orWhere('fin_certificado', 'like', '%' . $where . '%');

          $data_list = $data_list_2->union($data_list_1)
            ->offset($skip)
            ->limit($take)
            ->orderBy($order_by, $order_name)
            ->get();
        }

        if (empty($where)) {

          $data_list_1 = DB::table('afoc_afocats')
            ->join('afoc_vehiculos', 'afoc_afocats.vehiculo_placa', '=', 'afoc_vehiculos.placa')
            ->join('afoc_personas', 'afoc_afocats.persona_dni', '=', 'afoc_personas.dni')
            ->select(
              'afoc_afocats.numero as numero',
              'afoc_personas.dni as documento',
              DB::raw("concat(afoc_personas.nombre, ' ', afoc_personas.paterno, ' ', afoc_personas.materno) as afiliado"),
              'afoc_afocats.vehiculo_placa as placa',
              'afoc_afocats.fin_certificado as fin'
            );

          $data_list_2 = DB::table('afoc_afocats')
            ->join('afoc_vehiculos', 'afoc_afocats.vehiculo_placa', '=', 'afoc_vehiculos.placa')
            ->join('afoc_empresas', 'afoc_vehiculos.empresa_ruc', '=', 'afoc_empresas.ruc')
            ->select(
              'afoc_afocats.numero as numero',
              'afoc_empresas.ruc as documento',
              'afoc_empresas.nombre as afiliado',
              'afoc_afocats.vehiculo_placa as placa',
              'afoc_afocats.fin_certificado as fin'
            );

          $total = $data_list_2->union($data_list_1)
          ->get();

          $total = count($total);
        } else {
          $data_list_1 = DB::table('afoc_afocats')
            ->join('afoc_vehiculos', 'afoc_afocats.vehiculo_placa', '=', 'afoc_vehiculos.placa')
            ->join('afoc_personas', 'afoc_afocats.persona_dni', '=', 'afoc_personas.dni')
            ->select(
              'afoc_afocats.numero as numero',
              'afoc_personas.dni as documento',
              DB::raw("concat(afoc_personas.nombre, ' ', afoc_personas.paterno, ' ', afoc_personas.materno) as afiliado"),
              'afoc_afocats.vehiculo_placa as placa',
              'afoc_afocats.fin_certificado as fin'
            )
            ->where('numero', 'like', '%' . $where . '%')
            ->orWhere('dni', 'like', '%' . $where . '%')
            ->orWhere(DB::raw("concat(afoc_personas.nombre, ' ', afoc_personas.paterno, ' ', afoc_personas.materno)"), 'like', '%' . $where . '%')
            ->orWhere('placa', 'like', '%' . $where . '%')
            ->orWhere('fin_certificado', 'like', '%' . $where . '%');

          $data_list_2 = DB::table('afoc_afocats')
            ->join('afoc_vehiculos', 'afoc_afocats.vehiculo_placa', '=', 'afoc_vehiculos.placa')
            ->join('afoc_empresas', 'afoc_vehiculos.empresa_ruc', '=', 'afoc_empresas.ruc')
            ->select(
              'afoc_afocats.numero as numero',
              'afoc_empresas.ruc as documento',
              'afoc_empresas.nombre as afiliado',
              'afoc_afocats.vehiculo_placa as placa',
              'afoc_afocats.fin_certificado as fin'
            )
            ->where('numero', 'like', '%' . $where . '%')
            ->orWhere('ruc', 'like', '%' . $where . '%')
            ->orWhere('nombre', 'like', '%' . $where . '%')
            ->orWhere('placa', 'like', '%' . $where . '%')
            ->orWhere('fin_certificado', 'like', '%' . $where . '%');

          $total = $data_list_2->union($data_list_1)
            ->get();

          $total = count($total);
        }
      }

      foreach ($data_list as $data_table):

        $data = array_merge(
          array
          (
            "numero" => $data_table->numero,
            "dni" => $data_table->documento,
            "afiliado" => $data_table->afiliado,
            "placa" => $data_table->placa,
            "fin" => Carbon::createFromFormat('Y-m-d', $data_table->fin)->format('d/m/Y')
          )
        );
        //Asignamos un grupo de datos al array datas
        $datas[] = $data;
      endforeach;

      return response()->json(
        array(
          'current' => $line_quantity,
          'rowCount' => $line_number,
          'rows' => $datas,
          'total' => $total,
          'skip' => $skip,
          'take' => $take
        )
      );

  }

  public function exportarSunatRango(Request $request){

    set_time_limit(0);
    $dia_inicio = explode('/', $request->inicio)[0];
    $mes_inicio = explode('/', $request->inicio)[1];
    $anio_inicio = explode('/', $request->inicio)[2];
    $dia_fin = explode('/', $request->fin)[0];
    $mes_fin = explode('/', $request->fin)[1];
    $anio_fin = explode('/', $request->fin)[2];
    $inicio = $anio_inicio."-".$mes_inicio."-".$dia_inicio;
    $fin = $anio_fin."-".$mes_fin."-".$dia_fin;

    $afocats = \Afocat\Afocat::whereDate('inicio_certificado', '>=', $inicio)->whereDate('inicio_certificado', '<=', $fin)->orderBy('inicio_certificado')->get();
    $anulados = \Afocat\Anulado::whereDate('fecha', '>=', $inicio)->whereDate('fecha', '<=', $fin)->get();
    $datos = [];
    $total = 0;
    $nro_orden = 1;
    array_push($datos, ['NRO ORDEN', 'RUC', 'TIPO DE DOC.', 'NRO. DOC.', 'APELLIDO PATERNO', 'APELLIDO MATERNO', 'NOMBRES', 'RAZON SOCIAL',
      'MARCA VEHICULO', 'AÑO', 'COLOR', 'PLACA', 'USO VEH.', 'CLASE', 'NRO. ASIENTOS', 'MODELO', 'NRO. MOTOR', 'PROVINCIA', 'DEPARTAMENTO',
      'DIRECCION', 'TELEFONO', 'FECHA DE EMISION', 'FECHA DE INICIO', 'FECHA DE FIN', 'NRO. CAT', 'COSTO', '']);

    foreach ($afocats as $afocat) {
      if ($afocat->persona) {
        $tipo_documento = '01';
        $nro_documento = $afocat->persona->dni;
        $paterno = $afocat->persona->paterno;
        $materno = $afocat->persona->materno;
        $nombres = $afocat->persona->nombre;
        $empresa = '';
        $provincia = $afocat->persona->provincia;
        $departamento = $afocat->persona->departamento;
        $direccion = $afocat->persona->direccion;
        $telefono = $afocat->persona->telefono;
      }else{
        $tipo_documento = '06';
        $nro_documento = $afocat->empresa->ruc;
        $paterno = '';
        $materno = '';
        $nombres = '';
        $empresa = $afocat->empresa->nombre;
        $provincia = $afocat->empresa->provincia;
        $departamento = $afocat->empresa->departamento;
        $direccion = $afocat->empresa->direccion;
        $telefono = $afocat->empresa->telefono;
      }
      $estado = '';
      $monto = $afocat->monto;

      if ($afocat->inicio_contrato != $afocat->inicio_certificado) {
        if (Duplicado::where('numero', $afocat->numero)->first()) {
          $estado = 'DUP '.Duplicado::where('numero', $afocat->numero)->first()->afocat->numero;
        }else{
          $estado = 'DUPLICADO';
        }
        $monto = '';
      }
      array_push($datos,
        [
          $nro_orden,
          '20529005149',
          $tipo_documento,
          $nro_documento,
          $paterno,
          $materno,
          $nombres,
          $empresa,
          $afocat->vehiculo->marca,
          $afocat->vehiculo->anio,
          $afocat->vehiculo->color,
          $afocat->vehiculo->placa,
          $afocat->vehiculo->uso,
          $afocat->vehiculo->clase,
          $afocat->vehiculo->asientos,
          $afocat->vehiculo->modelo,
          $afocat->vehiculo->motor,
          $provincia,
          $departamento,
          $direccion,
          $telefono,
          $afocat->inicio_certificado,
          $afocat->inicio_contrato,
          $afocat->fin_contrato,
          $afocat->numero,
          $monto,
          $estado
        ]
      );
      $nro_orden++;
      $total += $monto;
    }

    foreach ($anulados as $anulado) {
      array_push($datos, [
        $nro_orden,
        '20529005149',
        '00',
        '00000000',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        $anulado->fecha,
        '-',
        '-',
        $anulado->numero,
        '-',
        'ANULADO'
      ]);
      $nro_orden++;
    }

    array_push($datos, [
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      'TOTAL',
      $total,
      ''
    ]);

    Excel::create('PADRON SUNAT '.date('d-m-Y'), function($excel) use ($datos) {

      $excel->setCreator('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setCompany('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setLastModifiedBy('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setKeywords('AFOCAT REGIONAL LEÓN DE HUÁNUCO PADRON GENERAL AFILIADOS SBS')
        ->setManager('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setDescription('Padrón general de todos los afiliados');

      $excel->sheet('PADRON GENERAL SBS', function($sheet) use ($datos) {

        $sheet->setFontFamily('Cambria');
        $sheet->setFontSize(10);
        $sheet->setFontBold(true);

        $sheet->cells('A1:Z1', function($cells){
          $cells->setBackground('#8be6e0');
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });

        $sheet->setColumnFormat(array(
          'A' => '0',
          'B' => '@',
          'C' => '@',
          'D' => '@',
          'E' => '@',
          'F' => '@',
          'G' => '@',
          'H' => '@',
          'I' => '@',
          'J' => '@',
          'K' => '@',
          'L' => '@',
          'M' => '0',
          'N' => '@',
          'O' => '@',
          'P' => '@',
          'Q' => '@',
          'R' => '@',
          'S' => '@',
          'T' => '@',
          'U' => '@',
          'V' => 'dd/mm/yyyy',
          'W' => 'dd/mm/yyyy',
          'X' => 'dd/mm/yyyy',
          'Y' => '@',
          'Z' => '0.00',
        ));

        $sheet->setWidth(array('A'=>7, 'B'=>12, 'C'=>8, 'D'=>11, 'E'=>15, 'F'=>15, 'G'=>27, 'H'=>46,
          'I'=>15, 'J'=>10, 'K'=>20, 'L'=>13, 'M'=>9, 'N'=>17, 'O'=>18, 'P'=>20, 'Q'=>15, 'R'=>16,
          'S'=>16, 'T'=>40, 'U'=>11, 'V'=>11, 'W'=>11, 'X'=>11, 'Y'=>11, 'Z'=>11, 'AA'=>13));

        $sheet->setHeight(['1'=>55]);

        $sheet->setBorder('A1:Z'.count($datos));

        $sheet->fromArray($datos, null, 'A1', false, false);

      });

    })->export('xlsx');

  }

  public function exportarSbsRango(Request $request){

    set_time_limit(0);
    $dia_inicio = explode('/', $request->inicio)[0];
    $mes_inicio = explode('/', $request->inicio)[1];
    $anio_inicio = explode('/', $request->inicio)[2];
    $dia_fin = explode('/', $request->fin)[0];
    $mes_fin = explode('/', $request->fin)[1];
    $anio_fin = explode('/', $request->fin)[2];
    $inicio = $anio_inicio."-".$mes_inicio."-".$dia_inicio;
    $fin = $anio_fin."-".$mes_fin."-".$dia_fin;

    $afocats = \Afocat\Afocat::whereDate('inicio_certificado', '>=', $inicio)->whereDate('inicio_certificado', '<=', $fin)->orderBy('inicio_certificado')->get();
    $anulados = \Afocat\Anulado::whereDate('fecha', '>=', $inicio)->whereDate('fecha', '<=', $fin)->get();
    $datos = [];
    $total_monto = 0;
    $total_extra = 0;
    $nro_orden = 1;
    array_push($datos, ['NRO ORDEN', 'FECHA DE EMISION', 'FECHA DE INICIO', 'FECHA DE FIN', 'NRO. CAT', 'APELLIDOS Y NOMBRES',
      'DNI/RUC', 'PLACA DE RODAJE', 'ZONA GEOGRÁFICA', 'CATEGORIA DEL VEHICULO', 'USO VEH.', 'CLASE DE VEHICULO',
      'VALOR DEL CAT', 'APORTE DE RIESGO', 'APORTE PARA GASTO ADM', 'APORTE EXTRAORDINARIO', '']);

    foreach ($afocats as $afocat) {
      if ($afocat->persona) {
        $nro_documento = $afocat->persona->dni;
        $nombre = $afocat->persona->paterno." ".$afocat->persona->materno." ".$afocat->persona->nombre;
        $provincia = $afocat->persona->provincia;
      }else{
        $nro_documento = $afocat->empresa->ruc;
        $nombre = $afocat->empresa->nombre;
        $provincia = $afocat->empresa->provincia;
      }
      $estado = '';
      $extra = '';
      $monto = $afocat->monto;
      if ($afocat->inicio_contrato != $afocat->inicio_certificado) {
        if(Duplicado::where('numero', $afocat->numero)->first()){
          $estado = 'DUP '.Duplicado::where('numero', $afocat->numero)->first()->afocat->numero;

        }else{
          $estado = 'DUPLICADO';
        }
        $monto = '';
        $extra = $afocat->monto;
      }
      array_push($datos,
        [
          $nro_orden,
          $afocat->inicio_certificado,
          $afocat->inicio_contrato,
          $afocat->fin_contrato,
          $afocat->numero,
          $nombre,
          $nro_documento,
          $afocat->vehiculo->placa,
          $provincia,
          $afocat->vehiculo->categoria,
          $afocat->vehiculo->uso,
          $afocat->vehiculo->clase,
          $monto,
          $monto*0.8,
          $monto*0.2,
          $extra,
          $estado
        ]
      );
      $total_monto += $monto;
      $total_monto += $extra;
      $nro_orden++;
    }

    foreach ($anulados as $anulado) {
      array_push($datos, [
        $nro_orden,
        $anulado->fecha,
        '-',
        '-',
        $anulado->numero,
        '-',
        '00000000',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        'ANULADO'
      ]);
      $nro_orden++;
    }

    array_push($datos, ['', '', '', '', '', '', '', '', '', '', '', '', $total_monto, $total_monto*0.8, $total_monto*0.2, $total_extra]);

    array_push($datos, ['', '', '', '', '', '', '', '', '', '', '', '', '', '1%', '', '']);

    array_push($datos, ['', '', '', '', '', '', '', '', '', '', '', '', '', ($total_monto*0.8)*0.01, 'FONDO DE COMPENSACION', '']);

    Excel::create('PADRON SBS ', function($excel) use ($datos) {

      $excel->setCreator('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setCompany('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setLastModifiedBy('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setKeywords('AFOCAT REGIONAL LEÓN DE HUÁNUCO PADRON GENERAL SBS')
        ->setManager('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setDescription('Padrón general SBS');

      $excel->sheet('PADRON GENERAL SBS', function($sheet) use ($datos) {

        $sheet->setFontFamily('Cambria');
        $sheet->setFontSize(10);
        $sheet->setFontBold(true);

        $sheet->cells('A1:P1', function($cells){
          $cells->setBackground('#FFFF00');
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });

        $sheet->setColumnFormat(array(
          'A' => '0',
          'B' => 'dd/mm/yyyy',
          'C' => 'dd/mm/yyyy',
          'D' => 'dd/mm/yyyy',
          'E' => '@',
          'F' => '@',
          'G' => '@',
          'H' => '@',
          'I' => '@',
          'J' => '@',
          'K' => '@',
          'L' => '@',
          'M' => '0.00',
          'N' => '0.00',
          'O' => '0.00',
          'P' => '0.00',
          'Q' => '@',
        ));

        $sheet->setWidth(array('A'=>10, 'B'=>11, 'C'=>11, 'D'=>11, 'E'=>11, 'F'=>46, 'G'=>13, 'H'=>11,
          'I'=>15, 'J'=>11, 'K'=>13, 'L'=>15, 'M'=>11, 'N'=>11, 'O'=>11, 'P'=>11, 'Q'=>15));

        $sheet->setHeight(['1'=>55]);

        $sheet->setBorder('A1:Q'.count($datos));

        $sheet->fromArray($datos, null, 'A1', false, false);

      });

    })->export('xlsx');
  }

  public function exportarGeneralRango(Request $request){

    set_time_limit(0);
    $dia_inicio = explode('/', $request->inicio)[0];
    $mes_inicio = explode('/', $request->inicio)[1];
    $anio_inicio = explode('/', $request->inicio)[2];
    $dia_fin = explode('/', $request->fin)[0];
    $mes_fin = explode('/', $request->fin)[1];
    $anio_fin = explode('/', $request->fin)[2];
    $inicio = $anio_inicio."-".$mes_inicio."-".$dia_inicio;
    $fin = $anio_fin."-".$mes_fin."-".$dia_fin;

    $afocats = \Afocat\Afocat::whereDate('inicio_certificado', '>=', $inicio)->whereDate('inicio_certificado', '<=', $fin)->orderBy('inicio_certificado')->get();
    $anulados = \Afocat\Anulado::whereDate('fecha', '>=', $inicio)->whereDate('fecha', '<=', $fin)->get();
    $datos = [];
    $total_monto = 0;
    $total_extra = 0;
    $nro_orden = 1;
    array_push($datos, ['NOR. ORDEN', 'DNI/RUC', 'ESTADO', 'MES', 'NRO. CAT', 'EMISION', 'INICIO', 'FIN',
      'NOMBRES Y APELLIDOS/RAZON SOCIAL', 'PLACA', 'PROVINCIA', 'CATEGORIA', 'USO', 'CLASE', 'PRECIO DE COSTO', 'MONTO EXTRAORDINARIO']);

    foreach ($afocats as $afocat) {
      $mesAnio = $this->mesLetras(explode('/', $afocat->inicio_certificado)[1]);

      if ($persona = $afocat->persona) {
        $nro_documento = $afocat->persona->dni;
        $nombre = $afocat->persona->paterno." ".$afocat->persona->materno." ".$afocat->persona->nombre;
        $provincia = $afocat->persona->provincia;
      }else{
        $nro_documento = $afocat->empresa->ruc;
        $nombre = $afocat->empresa->nombre;
        $provincia = $afocat->empresa->provincia;
      }
      $estado = '';
      $extra = '';
      $monto = $afocat->monto;
      if ($afocat->inicio_contrato != $afocat->inicio_certificado) {
        if(Duplicado::where('numero', $afocat->numero)->first()){
          $estado = 'DUP '.Duplicado::where('numero', $afocat->numero)->first()->afocat->numero;

        }else{
          $estado = 'DUPLICADO';
        }
        $monto = '';
        $extra = $afocat->monto;
      }
      array_push($datos,
        [
          $nro_orden,
          $nro_documento,
          $mesAnio,
          $estado,
          $afocat->numero,
          $afocat->inicio_certificado,
          $afocat->inicio_contrato,
          $afocat->fin_contrato,
          $nombre,
          $afocat->vehiculo->placa,
          $provincia,
          $afocat->vehiculo->categoria,
          $afocat->vehiculo->uso,
          $afocat->vehiculo->clase,
          $monto,
          $extra
        ]
      );
      $nro_orden++;
      $total_monto += $monto;
      $total_extra += $extra;
    }

    foreach ($anulados as $anulado) {
      $mesAnio = $this->mesLetras(explode('/', $anulado->fecha)[1]);
      array_push($datos, [
        $nro_orden,
        '00000000',
        $mesAnio,
        'ANULADO',
        $anulado->numero,
        $anulado->fecha,
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-'
      ]);
      $nro_orden++;
    }

    array_push($datos, [
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      'TOTAL',
      $total_monto,
      $total_extra
    ]);

    Excel::create('PADRON GRAL', function($excel) use ($datos) {

      $excel->setCreator('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setCompany('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setLastModifiedBy('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setKeywords('AFOCAT REGIONAL LEÓN DE HUÁNUCO PADRON GENERAL SBS')
        ->setManager('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setDescription('Padrón general SBS');

      $excel->sheet('PADRON GENERAL', function($sheet) use ($datos) {

        $sheet->setFontFamily('Cambria');
        $sheet->setFontSize(10);
        $sheet->setFontBold(true);

        $sheet->cells('A1:P1', function($cells){
          $cells->setBackground('#9f9f9f');
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });

        $sheet->setColumnFormat(array(
          'A' => '0',
          'B' => '@',
          'C' => '@',
          'D' => '@',
          'E' => '@',
          'F' => 'dd/mm/yyyy',
          'G' => 'dd/mm/yyyy',
          'H' => 'dd/mm/yyyy',
          'I' => '@',
          'J' => '@',
          'K' => '@',
          'L' => '@',
          'M' => '@',
          'N' => '@',
          'O' => '0.00',
          'P' => '0.00',
        ));

        $sheet->setWidth(array('A'=>7, 'B'=>11, 'C'=>11, 'D'=>15, 'E'=>9, 'F'=>11, 'G'=>11, 'H'=>11,
          'I'=>40, 'J'=>11, 'K'=>13, 'L'=>10, 'M'=>11, 'N'=>15, 'O'=>11, 'P'=>11));

        $sheet->setHeight(['1'=>45]);

        $sheet->setBorder('A1:P'.count($datos));

        $sheet->fromArray($datos, null, 'A1', false, false);

      });

    })->export('xlsx');
  }

  public function exportarVendedoresRango(Request $request){

    set_time_limit(0);
    $dia_inicio = explode('/', $request->inicio)[0];
    $mes_inicio = explode('/', $request->inicio)[1];
    $anio_inicio = explode('/', $request->inicio)[2];
    $dia_fin = explode('/', $request->fin)[0];
    $mes_fin = explode('/', $request->fin)[1];
    $anio_fin = explode('/', $request->fin)[2];
    $inicio = $anio_inicio."-".$mes_inicio."-".$dia_inicio;
    $fin = $anio_fin."-".$mes_fin."-".$dia_fin;

    $afocats = \Afocat\Afocat::whereDate('inicio_certificado', '>=', $inicio)->whereDate('inicio_certificado', '<=', $fin)->orderBy('inicio_certificado')->get();
    $anulados = \Afocat\Anulado::whereDate('fecha', '>=', $inicio)->whereDate('fecha', '<=', $fin)->get();
    $datos = [];
    $nro_orden = 1;
    array_push($datos, ['NO. ORDEN', 'DNI/RUC', 'MES', 'ESTADO', 'NRO. CAT', 'EMISION', 'INICIO', 'FIN',
      'APELLIDOS Y NOMBRES/RAZON SOCIAL', 'PLACA', 'PROVINCIA', 'CATEGORIA', 'USO', 'CLASE', 'PRECIO DE COSTO SBS',
      'PRECIO DE VENTA', 'AFILIADOR']);
    $total_sbs = 0;
    $total_venta = 0;
    foreach ($afocats as $afocat) {
      $mesAnio = $this->mesLetras(explode('/', $afocat->inicio_certificado)[1]);
      if ($afocat->persona) {
        $nro_documento = $afocat->persona->dni;
        $nombre = $afocat->persona->paterno." ".$afocat->persona->materno." ".$afocat->persona->nombre;
        $provincia = $afocat->persona->provincia;
      }else{
        $nro_documento = $afocat->empresa->ruc;
        $nombre = $afocat->empresa->nombre;
        $provincia = $afocat->empresa->provincia;
      }
      $estado = '';
      $monto = $afocat->monto;
      $vendedor = $afocat->vendedor;
      if ($afocat->inicio_contrato != $afocat->inicio_certificado) {
        if (Duplicado::where('numero', $afocat->numero)->first()) {
          # code...
          $estado = 'DUP '.Duplicado::where('numero', $afocat->numero)->first()->afocat->numero;

          if ($afocat->actualizacion) {
            # code...
            $vendedor = 'ACT '.Duplicado::where('numero', $afocat->numero)->first()->afocat->numero;
          }else{
            $vendedor = $estado;
          }
        }else{
          $estado = 'DUPLICADO';
          $vendedor = 'ACTUALIZACION';

        }
        $monto = '';
      }
      array_push($datos,
        [
          $nro_orden,
          $nro_documento,
          $mesAnio,
          $estado,
          $afocat->numero,
          $afocat->inicio_certificado,
          $afocat->inicio_contrato,
          $afocat->fin_contrato,
          $nombre,
          $afocat->vehiculo->placa,
          $provincia,
          $afocat->vehiculo->categoria,
          $afocat->vehiculo->uso,
          $afocat->vehiculo->clase,
          $monto,
          $afocat->monto + $afocat->extraordinario,
          $vendedor
        ]
      );
      $total_sbs += $monto;
      $total_venta += $afocat->monto + $afocat->extraordinario;
      $nro_orden++;
    }

    foreach ($anulados as $anulado) {
      $mesAnio = $this->mesLetras(explode('/', $anulado->fecha)[1]);
      $estado = 'ANULADO';
      if ($anulado->denuncia) {
        $estado = 'DENUNCIA';
      }
      array_push($datos, [
        $nro_orden,
        '00000000',
        $mesAnio,
        'ANULADO',
        $anulado->numero,
        $anulado->fecha,
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        '-',
        $estado
      ]);
      $nro_orden++;
    }

    array_push($datos, [
      '', '', '', '', '', '', '', '', '', '', '', '', '', '', $total_sbs, $total_venta, ''
    ]);

    array_push($datos, [
      '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'EXCEDENTE', $total_venta-$total_sbs
    ]);

    Excel::create('PADRON GRAL', function($excel) use ($datos) {

      $excel->setCreator('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setCompany('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setLastModifiedBy('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setKeywords('AFOCAT REGIONAL LEÓN DE HUÁNUCO PADRON GENERAL SBS')
        ->setManager('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setDescription('Padrón general SBS');

      $excel->sheet('PADRON GENERAL', function($sheet) use ($datos) {

        $sheet->setFontFamily('Cambria');
        $sheet->setFontSize(10);
        $sheet->setFontBold(true);

        $sheet->cells('A1:Q1', function($cells){
          $cells->setBackground('#BF0000');
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });

        $sheet->setColumnFormat(array(
          'A' => '0',
          'B' => '@',
          'C' => '@',
          'D' => '@',
          'E' => '@',
          'F' => 'dd/mm/yyyy',
          'G' => 'dd/mm/yyyy',
          'H' => 'dd/mm/yyyy',
          'I' => '@',
          'J' => '@',
          'K' => '@',
          'L' => '@',
          'M' => '@',
          'N' => '@',
          'O' => '0.00',
          'P' => '0.00',
          'Q' => '@',
        ));

        $sheet->setWidth(array('A'=>7, 'B'=>11, 'C'=>11, 'D'=>15, 'E'=>9, 'F'=>11, 'G'=>11, 'H'=>11,
          'I'=>40, 'J'=>11, 'K'=>13, 'L'=>10, 'M'=>11, 'N'=>15, 'O'=>11, 'P'=>11, 'Q'=>17));

        $sheet->setBorder('A1:Q'.count($datos));

        $sheet->setHeight(['1'=>45]);

        $sheet->fromArray($datos, null, 'A1', false, false);

      });

    })->export('xlsx');
  }

  public function mostrarFormularioReporteDiario(){

    return view('afocat.excel.reporteDiario');
  }

  public function exportarReporteDiario(Request $request){

    $cats = Afocat::whereDate('registro', Carbon::createFromFormat('d/m/Y', $request->dia)->format('Y-m-d'))->get();
    $datos = [];
    $nro_orden = 1;
    array_push($datos, ['NO. ORDEN', 'DNI/RUC', 'MES', 'ESTADO', 'NRO. CAT', 'EMISION', 'INICIO', 'FIN',
      'APELLIDOS Y NOMBRES/RAZON SOCIAL', 'PLACA', 'PROVINCIA', 'CATEGORIA', 'USO', 'CLASE', 'PRECIO DE COSTO SBS',
      'PRECIO DE VENTA', 'AFILIADOR']);
    $total_sbs = 0;
    $total_venta = 0;
    foreach ($cats as $afocat) {
      $mesAnio = $this->mesLetras(explode('/', $afocat->inicio_certificado)[1]);
      if ($persona = $afocat->persona) {
        $nro_documento = $persona->dni;
        $nombre = $persona->paterno." ".$persona->materno." ".$persona->nombre;
        $provincia = $persona->provincia;
      }else{
        $nro_documento = $afocat->empresa->ruc;
        $nombre = $afocat->empresa->nombre;
        $provincia = $afocat->empresa->provincia;
      }
      $estado = '';
      $monto = $afocat->monto;
      $vendedor = $afocat->vendedor;
      if ($afocat->inicio_contrato != $afocat->inicio_certificado) {
        if (Duplicado::where('numero', $afocat->numero)->first()) {
          # code...
          $estado = 'DUP '.Duplicado::where('numero', $afocat->numero)->first()->afocat->numero;

          if ($afocat->actualizacion) {
            # code...
            $vendedor = 'ACT '.Duplicado::where('numero', $afocat->numero)->first()->afocat->numero;
          }else{
            $vendedor = $estado;
          }
        }else{
          $estado = 'DUPLICADO';
          $vendedor = 'ACTUALIZACION';

        }
        $monto = '';
      }
      array_push($datos,
        [
          $nro_orden,
          $nro_documento,
          $mesAnio,
          $estado,
          $afocat->numero,
          $afocat->inicio_certificado,
          $afocat->inicio_contrato,
          $afocat->fin_contrato,
          $nombre,
          $afocat->vehiculo->placa,
          $provincia,
          $afocat->vehiculo->categoria,
          $afocat->vehiculo->uso,
          $afocat->vehiculo->clase,
          $monto,
          $afocat->monto + $afocat->extraordinario,
          $vendedor
        ]
      );
      $total_sbs += $monto;
      $total_venta += $afocat->monto + $afocat->extraordinario;
      $nro_orden++;
    }

    array_push($datos, [
      '', '', '', '', '', '', '', '', '', '', '', '', '', '', $total_sbs, $total_venta, ''
    ]);

    array_push($datos, [
      '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'EXCEDENTE', $total_venta-$total_sbs
    ]);
    array_push($datos, [
      '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
    ]);
    array_push($datos, [
      '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
    ]);
    array_push($datos, [
      '', '', '', '', '', '', '', '', '', '', '', '', '', $request->dia, '80%', $total_sbs*0.8, ''
    ]);
    array_push($datos, [
      '', '', '', '', '', '', '', '', '', '', '', '', '', '', '20%', $total_sbs*0.2, ''
    ]);
    array_push($datos, [
      '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $total_sbs, ''
    ]);

    Excel::create('REPORTE DIARIO '.$request->dia, function($excel) use ($datos) {

      $excel->setCreator('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setCompany('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setLastModifiedBy('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setKeywords('AFOCAT REGIONAL LEÓN DE HUÁNUCO PADRON GENERAL SBS')
        ->setManager('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setDescription('Padrón general SBS');

      $excel->sheet('PADRON GENERAL', function($sheet) use ($datos) {

        $sheet->setFontFamily('Cambria');
        $sheet->setFontSize(10);
        $sheet->setFontBold(true);

        $sheet->cells('A1:Q1', function($cells){
          $cells->setBackground('#00ff19');
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });

        $sheet->cells('N'.(count($datos)-2).':P'.count($datos), function($cells){
          $cells->setBackground('#00ff19');
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });

        $sheet->setColumnFormat(array(
          'A' => '0',
          'B' => '@',
          'C' => '@',
          'D' => '@',
          'E' => '@',
          'F' => 'dd/mm/yyyy',
          'G' => 'dd/mm/yyyy',
          'H' => 'dd/mm/yyyy',
          'I' => '@',
          'J' => '@',
          'K' => '@',
          'L' => '@',
          'M' => '@',
          'N' => '@',
          'O' => '0.00',
          'P' => '0.00',
          'Q' => '@',
        ));

        $sheet->setWidth(array('A'=>7, 'B'=>11, 'C'=>11, 'D'=>15, 'E'=>9, 'F'=>11, 'G'=>11, 'H'=>11,
          'I'=>40, 'J'=>11, 'K'=>13, 'L'=>10, 'M'=>11, 'N'=>15, 'O'=>11, 'P'=>11, 'Q'=>17));

        $sheet->setBorder('A1:Q'.count($datos));

        $sheet->setHeight(['1'=>45]);

        $sheet->fromArray($datos, null, 'A1', false, false);

      });

    })->export('xlsx');
  }

}
