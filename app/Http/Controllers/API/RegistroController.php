<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Registro;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegistroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //return $request->all();
        $var = Registro::where('user_id',$request['userid'])->where('fecha',Carbon::parse($request['fecha']))->first();
        //if($request['userid'] == 1){
        /*if(!empty($var)){
            return $var->llegadam;
        }else{
            return 'esta vacio';
        }*/
        if ($request['marca'] === 'maniana')
        {
            if(empty($var->llegadam)){
                Registro::create([
                    'user_id'    => $request['userid'],
                    'fecha'      => Carbon::parse($request['fecha']),
                    'horario_id' => $request['horarioid'],
                    'llegadam'   => Carbon::parse($request['hora']),
                    'atraso'     => $request['atraso'],
                ]);
                return response()->json(['message' => 'Registró su ingreso en esta mañana con éxito!'], 200);
            }else{
                return response()->json(['message' => 'Ya se registró tu entrada en esta mañana!'], 422);
            }
        } elseif ($request['marca'] === 'retirom') {
            if(empty($var->retirom)){
                Registro::create([
                    'user_id'    => $request['userid'],
                    'fecha'      => Carbon::parse($request['fecha']),
                    'horario_id' => $request['horarioid'],
                    'retirom'   => Carbon::parse($request['hora']),
                    'atraso'     => $request['atraso'],
                ]);
                return response()->json(['message' => 'Registró su salida en esta mañana con exitoso!'], 200);
            }else{
                return response()->json(['message' => 'Ya se registró tu salida en esta mañana!'], 422);
            }
        } else {
            if ($request['marca'] === 'tarde'){
                if(empty($var->llegadat)){
                    Registro::create([
                        'user_id'    => $request['userid'],
                        'fecha'      => Carbon::parse($request['fecha']),
                        'horario_id' => $request['horarioid'],
                        'llegadat'   => Carbon::parse($request['hora']),
                        'atraso'     => $request['atraso'],
                    ]);
                    return response()->json(['message' => 'Registró su entrada en esta tarde con exitoso!'], 200);
                }else{
                    return response()->json(['message' => 'Ya se registró tu entrada en esta tarde!'], 422);
                }
            }else{
                if(empty($var->retirot)){
                    Registro::create([
                        'user_id'    => $request['userid'],
                        'fecha'      => Carbon::parse($request['fecha']),
                        'horario_id' => $request['horarioid'],
                        'retirot'   => Carbon::parse($request['hora']),
                        'atraso'     => $request['atraso'],
                    ]);
                    return response()->json(['message' => 'Registró su salida en esta tarde con exitoso!'], 200);
                }else{
                    return response()->json(['message' => 'Ya se registró tu salida en esta tarde!'], 422);
                }

            }
        }

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
    public function edit($id)
    {
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
}
