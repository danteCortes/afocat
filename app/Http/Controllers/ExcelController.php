<?php

namespace Afocat\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use DB;
use Afocat\Afocat;
use Afocat\Duplicado;

class ExcelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()  {
        return view('afocat.excel.nuevo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {set_time_limit(0);
      $mes = explode('/', $request->mes_anio)[0];
      $anio = explode('/', $request->mes_anio)[1];

      $anulados = \Afocat\Anulado::whereMonth('fecha', $mes)->whereYear('fecha', $anio)->get();
      $afocats = \Afocat\Afocat::whereMonth('inicio_certificado', $mes)->whereYear('inicio_certificado', $anio)->get();
      $datos = [];
      $nro_orden = 1;
      $total = 0;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Exporta a un documento excel la relación de todos
     * los CAT's vencidos en los últimos 30 días.
     *
     * @return Excel
     */
    public function exportarCatsVencidos(){

      set_time_limit(0);

      $vencidos = Afocat::whereDate('fin_certificado', '<=', date('Y-m-d'))
        ->whereDate('fin_certificado', '>', date('Y-m-d', strtotime('-1 month')))->get();

      $datos = [];
      $nro_orden = 1;
      array_push($datos, ['NRO ORDEN', 'RUC', 'TIPO DE DOC.', 'NRO. DOC.', 'APELLIDOS Y NOMBRES', 'RAZON SOCIAL', 'MARCA VEHICULO', 'AÑO',
        'COLOR', 'PLACA', 'USO VEH.', 'CLASE', 'NRO. ASIENTOS', 'MODELO', 'NRO. MOTOR', 'PROVINCIA', 'DEPARTAMENTO', 'DIRECCION',
        'TELEFONO', 'FECHA DE EMISION', 'FECHA DE INICIO', 'FECHA DE FIN', 'NRO. CAT', 'VENDEDOR']);

      foreach($vencidos as $cat){

        if ($cat->vehiculo->persona) {
          $tipo_documento = '01';
          $nro_documento = $cat->vehiculo->persona->dni;
          $afiliado = $cat->vehiculo->persona->nombre;
          $empresa = '';
          $provincia = $cat->vehiculo->persona->provincia;
          $departamento = $cat->vehiculo->persona->departamento;
          $direccion = $cat->vehiculo->persona->direccion;
          $telefono = $cat->vehiculo->persona->telefono;
        }else{
          $tipo_documento = '06';
          $nro_documento = $cat->vehiculo->empresa->ruc;
          $afiliado = '';
          $empresa = $cat->vehiculo->empresa->nombre;
          $provincia = $cat->vehiculo->empresa->provincia;
          $departamento = $cat->vehiculo->empresa->departamento;
          $direccion = $cat->vehiculo->empresa->direccion;
          $telefono = $cat->vehiculo->empresa->telefono;
        }
        $vendedor = $cat->vendedor;
        if($cat->inicio_contrato != $cat->inicio_certificado){
          if(Duplicado::where('numero', $cat->numero)->first()){
            $vendedor = Duplicado::where('numero', $cat->numero)->first()->afocat->vendedor;
          }
        }

        array_push($datos,
          [
            $nro_orden,
            '20529005149',
            $tipo_documento,
            $nro_documento,
            $afiliado,
            $empresa,
            $cat->vehiculo->marca,
            $cat->vehiculo->anio,
            $cat->vehiculo->color,
            $cat->vehiculo->placa,
            $cat->vehiculo->uso,
            $cat->vehiculo->clase,
            $cat->vehiculo->asientos,
            $cat->vehiculo->modelo,
            $cat->vehiculo->motor,
            $provincia,
            $departamento,
            $direccion,
            $telefono,
            $cat->inicio_certificado,
            $cat->inicio_contrato,
            $cat->fin_contrato,
            $cat->numero,
            $cat->vendedor
          ]
        );
        $nro_orden++;
      }

      Excel::create('Reporte de vencidos '.date('d-m-Y'), function($excel) use ($datos) {

        $excel->setCreator('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
          ->setCompany('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
          ->setLastModifiedBy('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
          ->setKeywords('AFOCAT REGIONAL LEÓN DE HUÁNUCO PADRON DE CATS VENCIDOS')
          ->setManager('AFOCAT REGIONAL LEÓN DE HUÁNUCO')
          ->setDescription('Padrón general de todos CAT\'s Vencidos');

        $excel->sheet('PADRON DE VENCIDOS', function($sheet) use ($datos) {

          $sheet->setFontFamily('Cambria');
          $sheet->setFontSize(10);
          $sheet->setFontBold(true);

          $sheet->cells('A1:X1', function($cells){
            $cells->setBackground('#ed4700');
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
            'T' => 'dd/mm/yyyy',
            'U' => 'dd/mm/yyyy',
            'V' => 'dd/mm/yyyy',
            'W' => '@',
            'X' => '@',
          ));

          $sheet->setWidth(array('A'=>7, 'B'=>12, 'C'=>8, 'D'=>11, 'E'=>46, 'F'=>46, 'G'=>12, 'H'=>6,
            'I'=>24, 'J'=>10, 'K'=>13, 'L'=>13, 'M'=>9, 'N'=>26,
            'O'=>18, 'P'=>15, 'Q'=>13, 'R'=>46, 'S'=>12, 'T'=>11, 'U'=>11, 'V'=>11, 'W'=>9, 'X'=>15));

          $sheet->setHeight(['1'=>55]);

          $sheet->setBorder('A1:X'.count($datos));

          $sheet->fromArray($datos, null, 'A1', false, false);

        });

      })->export('xlsx');

    }
}
