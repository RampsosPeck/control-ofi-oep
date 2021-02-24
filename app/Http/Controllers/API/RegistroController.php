<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RegistroResource;
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
        if(\Gate::allows('isAdmin') || \Gate::allows('isAuthor'))
        {
            //return User::orderBy('id','DESC')->paginate(10);
            $registros = Registro::latest()->paginate(10);
            return RegistroResource::collection($registros);
        }
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
        //return $request;
        $registro = Registro::where('user_id',$request['userid'])->where('fecha',Carbon::parse($request['fecha']))->first();

        if($registro)
        {
            if ($request['marca'] === 'maniana')
            {
                if(empty($registro->llegadam)){
                    $registro->user_id = $request['userid'];
                    $registro->fecha   = Carbon::parse($request['fecha']);
                    $registro->horario_id = $request['horarioid'];
                    $registro->llegadam   = Carbon::parse($request['hora']);
                    $registro->atraso1    = $request['atraso'];
                    $registro->save();
                    return response()->json(['message' => 'Registró su ingreso en esta mañana con éxito!'], 200);
                }else{
                    return response()->json(['message' => 'Ya se registró tu entrada en esta mañana!'], 422);
                }
            } elseif ($request['marca'] === 'mediodia') {
                if(empty($registro->retirom)){
                    $registro->user_id = $request['userid'];
                    $registro->fecha   = Carbon::parse($request['fecha']);
                    $registro->horario_id = $request['horarioid'];
                    $registro->retirom   = Carbon::parse($request['hora']);
                    $registro->save();
                    return response()->json(['message' => 'Registró su salida en esta mañana con exitoso!'], 200);
                }else{
                    return response()->json(['message' => 'Ya se registró tu salida en esta mañana!'], 422);
                }
            } else {
                if ($request['marca'] === 'tarde'){
                    if(empty($registro->llegadat)){
                        $registro->user_id = $request['userid'];
                        $registro->fecha   = Carbon::parse($request['fecha']);
                        $registro->horario_id = $request['horarioid'];
                        $registro->llegadam   = Carbon::parse($request['hora']);
                        $registro->atraso2    = $request['atraso'];
                        $registro->save();
                        return response()->json(['message' => 'Registró su entrada en esta tarde con exitoso!'], 200);
                    }else{
                        return response()->json(['message' => 'Ya se registró tu entrada en esta tarde!'], 422);
                    }
                }else{
                    if(empty($registro->retirot)){
                        $registro->user_id = $request['userid'];
                        $registro->fecha   = Carbon::parse($request['fecha']);
                        $registro->horario_id = $request['horarioid'];
                        $registro->retirot   = Carbon::parse($request['hora']);
                        $registro->save();
                        return response()->json(['message' => 'Registró su salida en esta tarde con exitoso!'], 200);
                    }else{
                        return response()->json(['message' => 'Ya se registró tu salida en esta tarde!'], 422);
                    }

                }
            }
        }else{
            if ($request['marca'] === 'maniana')
            {
                Registro::create([
                    'user_id'    => $request['userid'],
                    'fecha'      => Carbon::parse($request['fecha']),
                    'horario_id' => $request['horarioid'],
                    'llegadam'   => Carbon::parse($request['hora']),
                    'atraso1'     => $request['atraso'],
                ]);
                return response()->json(['message' => 'Registró su ingreso en esta mañana con éxito!'], 200);
            } elseif ($request['marca'] === 'mediodia') {
                Registro::create([
                    'user_id'    => $request['userid'],
                    'fecha'      => Carbon::parse($request['fecha']),
                    'horario_id' => $request['horarioid'],
                    'retirom'   => Carbon::parse($request['hora']),
                ]);
                return response()->json(['message' => 'Registró su salida en esta mañana con exitoso!'], 200);
            } else {
                if ($request['marca'] === 'tarde'){
                    Registro::create([
                        'user_id'    => $request['userid'],
                        'fecha'      => Carbon::parse($request['fecha']),
                        'horario_id' => $request['horarioid'],
                        'llegadat'   => Carbon::parse($request['hora']),
                        'atraso2'     => $request['atraso'],
                    ]);
                    return response()->json(['message' => 'Registró su entrada en esta tarde con exitoso!'], 200);
                }else{
                    Registro::create([
                        'user_id'    => $request['userid'],
                        'fecha'      => Carbon::parse($request['fecha']),
                        'horario_id' => $request['horarioid'],
                        'retirot'   => Carbon::parse($request['hora']),
                    ]);
                    return response()->json(['message' => 'Registró su salida en esta tarde con exitoso!'], 200);
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
        //return "estas aqui xx";
        if(\Gate::allows('isAdmin') || \Gate::allows('isAuthor'))
        {
            //return User::orderBy('id','DESC')->paginate(10);
            $registros = Registro::where('user_id',$id)->get();
            return RegistroResource::collection($registros);
        }
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
    public function scandos(Request $request)
    {
        /*$momento = strtotime( $request['hora'] );
        $hora1 = strtotime( "10:00" );
        $hora2 = strtotime( "13:30" );
        $hora3 = strtotime( "16:00" );
        if( $momento < $hora1 ) {
            return 'maniana';
        } else if ( $momento < $hora2 ) {
            return 'mediodia';
        } else {
            if ($momento < $hora3){
                return 'tarde';
            }else{
                return 'nombre';
            }
        }*/
        //return $request;
        $registro = Registro::where('user_id',$request['userid'])->where('fecha',Carbon::parse($request['fecha']))->first();

        if($registro)
        {
            if ($request['marca'] === 'maniana')
            {
                if(empty($registro->llegadam)){
                    $registro->user_id = $request['userid'];
                    $registro->fecha   = Carbon::parse($request['fecha']);
                    $registro->horario_id = $request['horarioid'];
                    $registro->llegadam   = Carbon::parse($request['hora']);
                    $registro->save();
                    return response()->json(['message' => 'Registró su ingreso en esta mañana con éxito!'], 200);
                }else{
                    return response()->json(['message' => 'Ya se registró tu entrada en esta mañana!'], 422);
                }
            } elseif ($request['marca'] === 'mediodia') {
                if(empty($registro->retirom)){
                    $registro->user_id = $request['userid'];
                    $registro->fecha   = Carbon::parse($request['fecha']);
                    $registro->horario_id = $request['horarioid'];
                    $registro->retirom   = Carbon::parse($request['hora']);
                    $registro->save();
                    return response()->json(['message' => 'Registró su salida en esta mañana con exitoso!'], 200);
                }else{
                    return response()->json(['message' => 'Ya se registró tu salida en esta mañana!'], 422);
                }
            } else {
                if ($request['marca'] === 'tarde'){
                    if(empty($registro->llegadat)){
                        $registro->user_id = $request['userid'];
                        $registro->fecha   = Carbon::parse($request['fecha']);
                        $registro->horario_id = $request['horarioid'];
                        $registro->llegadam   = Carbon::parse($request['hora']);
                        $registro->save();
                        return response()->json(['message' => 'Registró su entrada en esta tarde con exitoso!'], 200);
                    }else{
                        return response()->json(['message' => 'Ya se registró tu entrada en esta tarde!'], 422);
                    }
                }else{
                    if(empty($registro->retirot)){
                        $registro->user_id = $request['userid'];
                        $registro->fecha   = Carbon::parse($request['fecha']);
                        $registro->horario_id = $request['horarioid'];
                        $registro->retirot   = Carbon::parse($request['hora']);
                        $registro->save();
                        return response()->json(['message' => 'Registró su salida en esta tarde con exitoso!'], 200);
                    }else{
                        return response()->json(['message' => 'Ya se registró tu salida en esta tarde!'], 422);
                    }

                }
            }
        }else{
            if ($request['marca'] === 'maniana')
            {
                Registro::create([
                    'user_id'    => $request['userid'],
                    'fecha'      => Carbon::parse($request['fecha']),
                    'horario_id' => $request['horarioid'],
                    'llegadam'   => Carbon::parse($request['hora']),
                ]);
                return response()->json(['message' => 'Registró su ingreso en esta mañana con éxito!'], 200);
            } elseif ($request['marca'] === 'mediodia') {
                Registro::create([
                    'user_id'    => $request['userid'],
                    'fecha'      => Carbon::parse($request['fecha']),
                    'horario_id' => $request['horarioid'],
                    'retirom'   => Carbon::parse($request['hora']),
                ]);
                return response()->json(['message' => 'Registró su salida en esta mañana con exitoso!'], 200);
            } else {
                if ($request['marca'] === 'tarde'){
                    Registro::create([
                        'user_id'    => $request['userid'],
                        'fecha'      => Carbon::parse($request['fecha']),
                        'horario_id' => $request['horarioid'],
                        'llegadat'   => Carbon::parse($request['hora']),
                    ]);
                    return response()->json(['message' => 'Registró su entrada en esta tarde con exitoso!'], 200);
                }else{
                    Registro::create([
                        'user_id'    => $request['userid'],
                        'fecha'      => Carbon::parse($request['fecha']),
                        'horario_id' => $request['horarioid'],
                        'retirot'   => Carbon::parse($request['hora']),
                    ]);
                    return response()->json(['message' => 'Registró su salida en esta tarde con exitoso!'], 200);
                }
            }
        }
    }
}
