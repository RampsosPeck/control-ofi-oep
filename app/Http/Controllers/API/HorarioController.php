<?php

namespace App\Http\Controllers\API;

use App\Horario;
use App\Http\Controllers\Controller;
use App\Http\Resources\HorarioResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HorarioController extends Controller
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
        if(\Gate::allows('isAdmin'))
        {
            //return User::orderBy('id','DESC')->paginate(10);
            $horarios = Horario::latest()->paginate(10);
            return HorarioResource::collection($horarios);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $horario = Horario::where('estado',true)->first();
        return  new HorarioResource($horario);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request['nombre'] === 'Continuo')
        {
            $this->validate($request,[
                'nombre' => ['required','unique:cargos,nombre'],
                'fecha' => ['required'],
                'ingresom' => 'date_format:H:i',
                'salidat' => 'date_format:H:i|after:ingresom',
            ]);
        }else{
            $this->validate($request,[
                'nombre' => ['required','unique:cargos,nombre'],
                'fecha' => ['required'],
                'ingresom' => 'date_format:H:i',
                'salidam' => 'date_format:H:i|after:ingresom',
                'ingresot' => 'date_format:H:i',
                'salidat' => 'date_format:H:i|after:ingresot',
            ]);
        }

        //return ['message' => 'I have your data'];
        Horario::create([
            'nombre' => $request['nombre'],
            'fecha' => $request['fecha'],
            'ingresom' => $request['ingresom'],
            'salidam' => $request['salidam'],
            'ingresot' => $request['ingresot'],
            'salidat' => $request['salidat'],
        ]);

        return response()->json(['message' => 'El horario '.$request['nombre']], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $this->authorize('isAdmin');

        Horario::where('id', $id)->update(['estado' => true]);
        Horario::where('id','!=', $id)->update(['estado' => false]);

        return  ['message' => 'El horario activado'];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        $horario = Horario::findOrFail($id);

        if($request['nombre'] === 'Continuo')
        {
            $this->validate($request,[
                'nombre'   => ['required'],
                'fecha'    => 'date_format:Y-m-d',
                'ingresom' => 'required',
                'salidat'  => 'required',
            ]);
        }else{
            $this->validate($request,[
                'nombre'   => ['required'],
                'ingresom' => 'required',
                'salidam'  => 'required',
                'ingresot' => 'required',
                'salidat'  => 'required',
            ]);
        }
        //$horario->update($request->all());
        $horario->nombre = $request['nombre'];
        $horario->fecha = Carbon::parse($request['fecha']);
        $horario->ingresom = $request['ingresom'];
        $horario->salidam = $request['salidam'];
        $horario->ingresot = $request['ingresot'];
        $horario->salidat = $request['salidat'];
        $horario->save();

        return ['message' => 'Se actualizó el horario!'];


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $horario = Horario::findOrFail($id);

        $horario->delete();

        return  ['message' => 'El horario fue eliminado'];
    }
}
