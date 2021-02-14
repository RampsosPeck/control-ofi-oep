<?php

namespace App\Http\Controllers\API;

use App\Cargo;
use App\Http\Controllers\Controller;
use App\Http\Resources\CargoResource;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CargoController extends Controller
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
            $cargos = Cargo::latest()->paginate(10);
            return CargoResource::collection($cargos);
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
        $this->validate($request,[
            'nombre' => ['required','unique:cargos,nombre'],
            'fecha' => ['required'],
        ]);

        //return ['message' => 'I have your data'];
        Cargo::create([
            'nombre' => $request['nombre'],
            'fecha' => $request['fecha'],
        ]);

        return response()->json(['message' => 'El cargo '.$request['nombre']], 200);
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

        $this->validate($request, [
            'nombre' => 'required|string|max:191',
        ]);
        //return $request->all();
        $cargo = Cargo::find($id);
        $cargo->nombre = $request['nombre'];
        $cargo->fecha = Carbon::parse($request['fecha']);
        $cargo->save();

        return ['message' => 'Se actualizó el cargo'];
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

        $cargo = Cargo::findOrFail($id);

        $cargo->delete();

        return  ['message' => 'El cargo fue eliminado'];
    }
}
