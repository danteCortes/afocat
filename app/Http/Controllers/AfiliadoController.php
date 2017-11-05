<?php

namespace Afocat\Http\Controllers;

use Illuminate\Http\Request;
use Afocat\Persona;
use DB;

class AfiliadoController extends Controller
{
  public function buscarAfiliado(Request $request)
  {
      $line_quantity = intVal($request->current);
      $line_number = intVal($request->rowCount);
      $where = $request->searchPhrase;
      $sort = $request->sort;

      if (isset($sort['dni'])) {
          $order_by = 'dni';
          $order_name = $sort['dni'];
      }
      if (isset($sort['afiliado'])) {
          $order_by = 'afiliado';
          $order_name = $sort['afiliado'];
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
          $personas = DB::table('afoc_personas')
            ->join('afoc_vehiculos', 'afoc_personas.dni', '=', 'afoc_vehiculos.persona_dni')
            ->select(
              'afoc_personas.dni as dni',
              DB::raw("concat(afoc_personas.nombre, ' ', afoc_personas.paterno, ' ', afoc_personas.materno) as afiliado")
            )
            ->distinct()
            ->offset($skip)
            ->limit($take)
            ->orderBy($order_by, $order_name)
            ->get();
        } else {
          $personas = DB::table('afoc_personas')
            ->join('afoc_vehiculos', 'afoc_personas.dni', '=', 'afoc_vehiculos.persona_dni')
            ->select(
              'afoc_personas.dni as dni',
              DB::raw("concat(afoc_personas.nombre, ' ', afoc_personas.paterno, ' ', afoc_personas.materno) as afiliado")
            )
            ->where('dni', 'like', '%'.$where.'%')
            ->orWhere(DB::raw("concat(afoc_personas.nombre, ' ', afoc_personas.paterno, ' ', afoc_personas.materno)"), 'like', '%'.$where.'%')
            ->distinct()
            ->offset($skip)
            ->limit($take)
            ->orderBy($order_by, $order_name)
            ->get();
        }

        if (empty($where)) {
          $total = DB::table('afoc_personas')
            ->join('afoc_vehiculos', 'afoc_personas.dni', '=', 'afoc_vehiculos.persona_dni')
            ->select(
              'afoc_personas.dni as dni',
              DB::raw("concat(afoc_personas.nombre, ' ', afoc_personas.paterno, ' ', afoc_personas.materno) as afiliado")
            )
            ->distinct()
            ->get();

          $total = count($total);
        } else {
          $total = DB::table('afoc_personas')
            ->join('afoc_vehiculos', 'afoc_personas.dni', '=', 'afoc_vehiculos.persona_dni')
            ->select(
              'afoc_personas.dni as dni',
              DB::raw("concat(afoc_personas.nombre, ' ', afoc_personas.paterno, ' ', afoc_personas.materno) as afiliado")
            )
            ->where('dni', 'like', '%'.$where.'%')
            ->orWhere(DB::raw("concat(afoc_personas.nombre, ' ', afoc_personas.paterno, ' ', afoc_personas.materno)"), 'like', '%'.$where.'%')
            ->distinct()
            ->get();

          $total = count($total);
        }
      }

      foreach ($personas as $persona):

        $data = array_merge(
          array
          (
            "dni" => $persona->dni,
            "afiliado" => $persona->afiliado
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
}
