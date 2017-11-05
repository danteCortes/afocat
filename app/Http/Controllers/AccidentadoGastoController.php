<?php

namespace Afocat\Http\Controllers;

use Afocat\AccidentadoGasto;
use Afocat\Accidentado;
use Afocat\Accidente;
use Afocat\Gasto;
use Illuminate\Http\Request;
use Excel;
use DB;
use Storage;
use Validator;

class AccidentadoGastoController extends Controller
{
  public function index(){
    $pagos = AccidentadoGasto::all();
    return view('pago.inicio')->with('pagos', $pagos);
  }

  public function create(){
    return view('pago.nuevo');
  }

  public function store(Request $request){

    Validator::make(
      $request->all(),
      [
        'accidentado_codigo'=>'required|exists:afoc_accidentados,codigo',
        'gasto_id'=>'required|numeric',
        'pagado'=>'nullable',
        'pendiente'=>'nullable',
        'fecha_limite'=>'nullable|date_format:d/m/Y'
      ]
    )->validate();

    $pago = AccidentadoGasto::where('accidentado_codigo', '=', $request->accidentado_codigo)->where('gasto_id', '=', $request->gasto_id)->first();
    if ($pago) {
      return redirect('pago/create')->with('error', 'EL CÓDIGO DEL ACCIDENTADO YA TIENE ESTE TIPO DE PAGO.');
    } else {
      $accidentadoGasto = new AccidentadoGasto;
      $accidentadoGasto->accidentado_codigo = $request->accidentado_codigo;
      $accidentadoGasto->gasto_id = $request->gasto_id;
      $accidentadoGasto->pagado = str_replace(' ', '', $request->pagado);
      if ($request->pendiente) {
        $accidentadoGasto->pendiente = str_replace(' ', '', $request->pendiente);
      }
      if($request->fecha_limite){
        $accidentadoGasto->fecha_limite = $this->formatoFecha($request->fecha_limite);
      }
      if($request->estado){
        $accidentadoGasto->estado = $request->estado;
      }else{
        $accidentadoGasto->estado = 0;
      }
      $accidentadoGasto->save();

      return redirect('pago')->with('correcto', 'EL PAGO AL ACCIDENTADO SE REGISTRÓ CON EXITO.');
    }
  }

  public function edit($id){
    $accidentadoGasto = AccidentadoGasto::find($id);
    return view('pago.editar')->with('pago', $accidentadoGasto);
  }

  public function update(Request $request, $id){
    $accidentado = Accidentado::find($request->accidentado_codigo);

    if ($accidentado) {
      $accidentadoGasto = AccidentadoGasto::find($id);
      $accidentadoGasto->accidentado_codigo = $request->accidentado_codigo;
      $accidentadoGasto->gasto_id = $request->gasto_id;
      $accidentadoGasto->pagado = str_replace(' ', '', $request->pagado);
      $accidentadoGasto->pendiente = str_replace(' ', '', $request->pendiente);
      if($request->fecha_limite){
        $accidentadoGasto->fecha_limite = $this->formatoFecha($request->fecha_limite);
      }
      if($request->estado){
        $accidentadoGasto->estado = $request->estado;
      }else{
        $accidentadoGasto->estado = 0;
      }
      $accidentadoGasto->save();

      return redirect('pago')->with('correcto', 'EL PAGO AL ACCIDENTADO SE MODIFICÓ CON EXITO.');
    } else {
      return redirect('pago/'.$id.'/edit')->with('error', 'EL CÓDIGO DEL ACCIDENTADO ES INCORRECTO.');
    }
  }

  /**
   * Se da formato a una fecha de 'd/m/Y' a 'Y-m-d' para ser guardado en la base de datos.
   *
   * @param String fecha
   * @return String
   */
  private function formatoFecha($fecha){

    $fecha = explode('/', $fecha);
    $fecha = $fecha[2]."-".$fecha[1]."-".$fecha[0];
    return $fecha;
  }

  public function destroy($id){
      $accidentadoGasto = AccidentadoGasto::find($id);
      $accidentadoGasto->delete();
      return redirect('pago')->with('advertencia', 'EL PAGO FUE BORRADO.');
  }

  public function buscarAccidentado(Request $request){
    $accidentado = Accidentado::find($request->accidentado_codigo);
    if ($accidentado) {
      return ['nombre'=>$accidentado->nombre];
    }else{
      return ['nombre'=>'NO SE ENCUENTRA DATOS DEL CODIGO DE ACCIDENTADO'];
    }
  }

  public function buscarMes(){
    return view('pago.buscarexcel');
  }

  public function descargarExcel(Request $request){

    $mes = explode('/', $request->mes_anio)[0];
    $anio = explode('/', $request->mes_anio)[1];

    $accidentes = Accidente::whereMonth('ocurrencia', $mes)->whereYear('ocurrencia', $anio)->get();

    $siniestros = [];
    $total_1_pagado = 0;
    $total_1_pendiente = 0;
    $total_2_pagado = 0;
    $total_2_pendiente = 0;
    $total_3_pagado = 0;
    $total_3_pendiente = 0;
    $total_4_pagado = 0;
    $total_4_pendiente = 0;
    $total_5_pagado = 0;
    $total_5_pendiente = 0;
    foreach ($accidentes as $accidente) {
      $cat = DB::table('afoc_afocats')->where('vehiculo_placa', '=', $accidente->vehiculo_placa)->orderBy('fin_contrato')->get()->first();
      $cat = \Afocat\Afocat::find($cat->numero);
      foreach ($accidente->accidentados as $accidentado) {
        $siniestro = [$cat->numero, $accidente->vehiculo_placa, $accidente->vehiculo->clase, $accidente->id, $accidentado->codigo, $accidente->zona,
          $accidente->ocurrencia, $accidente->notificacion, $accidentado->nombre];

        $total_pagado = 0;
        $total_pendiente = 0;
        foreach ($accidentado->pagos as $pago) {
          if ($pago->gasto_id == 1) {
            $siniestro[9] = number_format($pago->pagado, 2, '.', ' ');
            $siniestro[10] = number_format($pago->pendiente, 2, '.', ' ');
            $total_pagado += $pago->pagado;
            $total_pendiente += $pago->pendiente;
            $total_1_pagado += $pago->pagado;
            $total_1_pendiente += $pago->pendiente;
          } elseif ($pago->gasto_id == 2) {
            for ($i=9; $i < 11; $i++) {
              if (!isset($siniestro[$i])) {
                $siniestro[$i] = '-';
              }
            }
            $siniestro[11] = number_format($pago->pagado, 2, '.', ' ');
            $siniestro[12] = number_format($pago->pendiente, 2, '.', ' ');
            $total_pagado += $pago->pagado;
            $total_pendiente += $pago->pendiente;
            $total_2_pagado += $pago->pagado;
            $total_2_pendiente += $pago->pendiente;
          } elseif ($pago->gasto_id == 3) {
            for ($i=9; $i < 13; $i++) {
              if (!isset($siniestro[$i])) {
                $siniestro[$i] = '-';
              }
            }
            $siniestro[13] = number_format($pago->pagado, 2, '.', ' ');
            $siniestro[14] = number_format($pago->pendiente, 2, '.', ' ');
            $total_pagado += $pago->pagado;
            $total_pendiente += $pago->pendiente;
            $total_3_pagado += $pago->pagado;
            $total_3_pendiente += $pago->pendiente;
          } elseif ($pago->gasto_id == 4) {
            for ($i=9; $i < 15; $i++) {
              if (!isset($siniestro[$i])) {
                $siniestro[$i] = '-';
              }
            }
            $siniestro[15] = number_format($pago->pagado, 2, '.', ' ');
            $siniestro[16] = number_format($pago->pendiente, 2, '.', ' ');
            $total_pagado += $pago->pagado;
            $total_pendiente += $pago->pendiente;
            $total_4_pagado += $pago->pagado;
            $total_4_pendiente += $pago->pendiente;
          } elseif ($pago->gasto_id == 5) {
            for ($i=9; $i < 17; $i++) {
              if (!isset($siniestro[$i])) {
                $siniestro[$i] = '-';
              }
            }
            $siniestro[17] = number_format($pago->pagado, 2, '.', ' ');
            $siniestro[18] = number_format($pago->pendiente, 2, '.', ' ');
            $total_pagado += $pago->pagado;
            $total_pendiente += $pago->pendiente;
            $total_5_pagado += $pago->pagado;
            $total_5_pendiente += $pago->pendiente;
          }
        }
        for ($i=9; $i < 19; $i++) {
          if (!isset($siniestro[$i])) {
            $siniestro[$i] = '-';
          }
        }
        array_push($siniestro, $total_pagado);
        array_push($siniestro, $total_pendiente);
        array_push($siniestro, ($total_pendiente+$total_pagado));
        array_push($siniestro, $accidentado->forma);
        array_push($siniestro, $accidentado->cuenta);
        array_push($siniestro, $accidentado->a_cuenta);
        array_push($siniestros, $siniestro);
      }
    }

    array_push($siniestros, ['', '', '', '', '', '', '', '', '', number_format($total_1_pagado, 2, '.', ' '), number_format($total_1_pendiente, 2, '.', ' '),
    number_format($total_2_pagado, 2, '.', ' '), number_format($total_2_pendiente, 2, '.', ' '), number_format($total_3_pagado, 2, '.', ' '),
    number_format($total_3_pendiente, 2, '.', ' '), number_format($total_4_pagado, 2, '.', ' '), number_format($total_4_pendiente, 2, '.', ' '),
    number_format($total_5_pagado, 2, '.', ' '), number_format($total_5_pendiente, 2, '.', ' '),
    number_format($total_1_pagado+$total_2_pagado+$total_3_pagado+$total_4_pagado+$total_5_pagado, 2, '.', ' '),
    number_format($total_1_pendiente+$total_2_pendiente+$total_3_pendiente+$total_4_pendiente+$total_5_pendiente, 2, '.', ' '),
    number_format($total_1_pendiente+$total_2_pendiente+$total_3_pendiente+$total_4_pendiente+$total_5_pendiente+$total_1_pagado+$total_2_pagado+$total_3_pagado+$total_4_pagado+$total_5_pagado, 2, '.', ' ')]);

    Excel::create('Reporte de siniestros '.date('d-m-Y').'_'.time(), function($excel) use($siniestros){

      $excel->setCreator('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setCompany('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setLastModifiedBy('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setKeywords('AFOCAT REGIONAL LEÓN DE HUÁNUCO PADRON DE SINIESTROS')
        ->setManager('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
        ->setDescription('Padrón general de siniestros');

      $excel->sheet('PADRON DE SINIESTROS', function($sheet) use ($siniestros){

        $sheet->setFontFamily('Cambria');
        $sheet->setFontSize(11);
        $sheet->setFontBold(true);


        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->mergeCells('D1:D2');
        $sheet->mergeCells('E1:E2');
        $sheet->mergeCells('F1:F2');
        $sheet->mergeCells('G1:G2');
        $sheet->mergeCells('H1:H2');
        $sheet->mergeCells('I1:I2');
        $sheet->mergeCells('J1:K1');
        $sheet->mergeCells('L1:M1');
        $sheet->mergeCells('N1:O1');
        $sheet->mergeCells('P1:Q1');
        $sheet->mergeCells('R1:S1');
        $sheet->mergeCells('T1:T2');
        $sheet->mergeCells('U1:U2');
        $sheet->mergeCells('V1:V2');
        $sheet->mergeCells('W1:W2');
        $sheet->mergeCells('X1:X2');
        $sheet->mergeCells('Y1:Y2');

        $sheet->cells('A1:Y2', function($cells){
          $cells->setBackground('#8be6e0');
          $cells->setAlignment('center');
          $cells->setValignment('center');
        });

        $sheet->setColumnFormat(array(
          'A' => '@',
          'B' => '@',
          'C' => '@',
          'D' => '@',
          'E' => '@',
          'F' => 'dd/mm/yyyy',
          'G' => 'dd/mm/yyyy',
          'H' => '@',
          'I' => '0.00',
          'J' => '0.00',
          'K' => '0.00',
          'L' => '0.00',
          'M' => '0.00',
          'N' => '0.00',
          'O' => '0.00',
          'P' => '0.00',
          'Q' => '0.00',
          'R' => '0.00',
          'S' => '0.00',
          'T' => '0.00',
          'U' => '0.00',
          'v' => '0.00',
          'w' => '@',
          'x' => '0.00',
          'y' => '@',
        ));

        $sheet->setWidth(array('A'=>14, 'B'=>15, 'C'=>11, 'D'=>11, 'E'=>14, 'F'=>11, 'G'=>12, 'H'=>12, 'I'=>46, 'J'=>13, 'K'=>13, 'L'=>13, 'M'=>13, 'N'=>13,
          'O'=>13, 'P'=>13, 'Q'=>13, 'R'=>13, 'S'=>17, 'T'=>15, 'U'=>15, 'V'=>15, 'W'=>15, 'X'=>13, 'Y'=>15));

        $sheet->setHeight(['1'=>20, '2'=>55]);

        $datos = [];

        array_push($datos, ['N° DE CAT', 'PLACA DE RODAJE', 'CLASE', 'CODIGO DEL ACCIDENTE', 'CODIGO DEL SINIESTRO', 'ZONA GEOGRAFICA', 'FECHA DE OCURRENCIA',
          'FECHA DE NOTIFICACION A LA AFOCAT', 'NOMBRES DE LOS ACCIDENTADOS', 'GASTOS MEDICOS', '', 'INCAPACIDAD TEMPORAL', '', 'INVALIDEZ PERMANENTE', '',
          'SINIESTRO DE SEPELIO', '', 'INDEMNIZACION POR MUERTE', '', 'BENEFICIOS PAGADOS TOTAL', 'RECLAMOS PENDIENTES DE PAGO TOTAL CUENTA 26',
          'COSTO DE SINIESTRO TOTAL', 'FORMA DE PAGO', 'A - 82', 'ERA CUENTA A-82']);
        array_push($datos, ['', '', '', '', '', '', '', '', '', 'BENEFICIOS PAGADOS', 'RECLAMO PENDIENTE DE PAGO', 'BENEFICIOS PAGADOS',
          'RECLAMO PENDIENTE DE PAGO', 'BENEFICIOS PAGADOS', 'RECLAMO PENDIENTE DE PAGO', 'BENEFICIOS PAGADOS', 'RECLAMO PENDIENTE DE PAGO', 'BENEFICIOS PAGADOS',
          'RECLAMO PENDIENTE DE PAGO']);
        foreach ($siniestros as $siniestro) {
          array_push($datos, $siniestro);
        }

        $sheet->setBorder('A1:Y'.count($datos));

        $sheet->cells('I3:U'.count($datos), function($cells){
          $cells->setAlignment('right');
          $cells->setValignment('center');
        });

        $sheet->cells('A3:G'.count($datos), function($cells){
          $cells->setAlignment('right');
          $cells->setValignment('center');
        });

        $sheet->fromArray($datos, null, 'A1', false, false);
      });

    })->export('xlsx');
  }

  public function indemnizaciones(){
    $indemnizados = AccidentadoGasto::where('estado', 1)->get();
    return view('gasto.indemnizados')->with('indemnizados', $indemnizados);
  }

  public function mostrarFormularioSubirSiniestros(){

    return view('gasto.subirSiniestros');
  }

  public function subirSiniestros(Request $request){

    $padron = $request->file('padron');
    $nombre = time()."_".$padron->getClientOriginalName();
    Storage::disk('siniestros')->put($nombre, file_get_contents( $padron->getRealPath() ) );

    return redirect('padron-siniestros/'.$nombre);
  }
}
