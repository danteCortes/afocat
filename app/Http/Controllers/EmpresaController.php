<?php

namespace Afocat\Http\Controllers;

use Afocat\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $empresas = \Afocat\Empresa::all();
        return view('empresa.todos')->with('empresas', $empresas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Afocat\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function show(Empresa $empresa)
    {
        return view('empresa.mostrar')->with('empresa', $empresa);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Afocat\Empresa  $empresa
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresa $empresa)
    {
        return view('empresa.editar')->with('empresa', $empresa);
    }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Afocat\Empresa  $empresa
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Empresa $empresa)
  {
    $this->validate($request, [
      'ruc'=>'required|size:11',
      'nombre'=>'required|string',
      'direccion'=>'required',
      'provincia'=>'required',
      'representante'=>'nullable|string',
      'telefono'=>'size:9',
      'email'=>'nullable|email',
      'nacimiento'=>'nullable|date_format:d/m/Y'
    ]);

    if (isset($request->nacimiento)) {
      $nacimiento = date('Y-m-d', strtotime(str_replace('/', '-', $request->nacimiento)));
    }else{
      $nacimiento = null;
    }
    $empresa->ruc = $request->ruc;
    $empresa->nombre = mb_strtoupper($request->nombre);
    $empresa->direccion = mb_strtoupper($request->direccion);
    $empresa->provincia = mb_strtoupper($request->provincia);
    $empresa->departamento = mb_strtoupper($request->departamento);
    $empresa->representante = mb_strtoupper($request->representante);
    $empresa->telefono = $request->telefono;
    $empresa->email = $request->email;
    $empresa->nacimiento = $nacimiento;
    $empresa->save();

    return redirect('empresa')->with('correcto', 'LOS DATOS DE LA EMPRESA FUERON EDITADOS CON EXITO.');

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \Afocat\Empresa  $empresa
   * @return \Illuminate\Http\Response
   */
  public function destroy(Empresa $empresa)
  {
    $empresa->delete();
    return 1;
  }

  public function buscarEmpresa(Request $request){

    $line_quantity = intVal($request->current);
    $line_number = intVal($request->rowCount);
    $where = $request->searchPhrase;
    $sort = $request->sort;

    if (isset($sort['ruc'])) {
        $order_by = 'ruc';
        $order_name = $sort['ruc'];
    }
    if (isset($sort['razon'])) {
        $order_by = 'nombre';
        $order_name = $sort['razon'];
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

          $data_list = Empresa::offset($skip)
              ->limit($take)
              ->orderBy($order_by, $order_name)
              ->get();
      } else {
          $data_list = Empresa::offset($skip)
              ->limit($take)
              ->where('ruc', 'like', '%' . $where . '%')
              ->orWhere('nombre', 'like', '%' . $where . '%')
              ->orderBy($order_by, $order_name)
              ->get();
      }

        if (empty($where)) {

            $total = Empresa::count('ruc');
        } else {

            $total = Empresa::where('ruc', 'like', '%' . $where . '%')
                ->orWhere('nombre', 'like', '%' . $where . '%')
                ->count('ruc');
        }
    }

    foreach ($data_list as $data_table):

        $data = array_merge(
            array
            (
                "ruc" => $data_table['ruc'],
                "razon" => $data_table['nombre']
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
