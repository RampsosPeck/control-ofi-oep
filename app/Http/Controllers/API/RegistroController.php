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

        Registro::create([
            'user_id'    => $request['userid'],
            'fecha'      => Carbon::parse($request['fecha']),
            'horario_id' => $request['horarioid'],
            'llegadam'   => Carbon::createFromTimestampMs($request['hora'], 'America/La_Paz'),
            'atraso'     => $request['atraso'],
        ]);

        //Carbon::parse($request['hora'])
        /*if ($request['hora'] <= '10:00:00')
        {
            Registro::create([
                'user_id'    => $request['userid'],
                'fecha'      => Carbon::parse($request['fecha']),
                'horario_id' => 1,
                'llegadam'   => Carbon::createFromTimestampMs($request['hora'], 'America/La_Paz'),
                'atraso'     => $request['atraso'],
            ]);
        } elseif ($request['hora'] <= '13:00:00') {
            Registro::create([
                'user_id'    => $request['userid'],
                'fecha'      => Carbon::parse($request['fecha']),
                'horario_id' => 1,
                'retirom'    => strval($request['hora']),
                'atraso'     => $request['atraso'],
            ]);
        } else {
            if ($request['hora'] <= '16:00:00'){
                Registro::create([
                    'user_id'    => $request['userid'],
                    'fecha'      => Carbon::parse($request['fecha']),
                    'horario_id' => 1,
                    'llegadat'   => Carbon::parse($request['hora']),
                    'atraso'     => $request['atraso'],
                ]);
            }else{
                Registro::create([
                    'user_id'    => $request['userid'],
                    'fecha'      => Carbon::parse($request['fecha']),
                    'horario_id' => 1,
                    //'retirot'    => $request['hora'],
                    'retirot'    => Carbon::createFromTimestampMs($request['hora'], 'America/La_Paz'),
                    'atraso'     => $request['atraso'],
                ]);
            }
        }*/

        return response()->json(['message' => 'Registro exitoso!!!'], 200);

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
