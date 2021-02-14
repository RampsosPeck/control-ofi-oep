<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
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
        //$this->authorize('isAdmin');

        if(\Gate::allows('isAdmin') || \Gate::allows('isAuthor'))
        {
            //return User::orderBy('id','DESC')->paginate(10);
            $users = User::latest()->paginate(10);
            return UserResource::collection($users);
        }

    }
    public function personas()
    {
        //$this->authorize('isAdmin');

        if(\Gate::allows('isAdmin') || \Gate::allows('isAuthor'))
        {
            $users = User::where('type','user')->latest()->paginate(10);
            return UserResource::collection($users);
        }

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
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'cedula' => ['required','unique:users,cedula'],
            'telefono' => ['required','numeric','digits:8','unique:users,telefono'],
            'cargo_id' => 'required',
        ]);

        //return ['message' => 'I have your data'];
        User::create([
            'name' => $request['name'],
            'cedula' => $request['cedula'],
            'telefono' => $request['telefono'],
            'email' => $request['email'],
            'type' => 'user',
            'bio' => $request['bio'],
            'cargo_id' => $request['cargo_id'],
            //'password' => Hash::make($request['celular']),
        ]);

        return response()->json(['message' => 'Hola! '.$request['name']], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updateProfile(Request $request)
    {
        $user = auth('api')->user();

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|min:5'
        ]);


        $currentPhoto = $user->photo;

        if( $request->photo != $currentPhoto )
        {
            $name = time().'.'.explode('/',explode(':',substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo' => $name]);

            $userPhoto = public_path('img/profile/').$currentPhoto;
            if( file_exists($userPhoto) )
            {
                @unlink($userPhoto);
            }

        }


        if(!empty($request->password))
        {
            $request->merge(['password' => Hash::make($request['password'])]);
        }


        $user->update($request->all());

        return ['message'=> "Success"];
    }

    public function profile()
    {
        return auth('api')->user();
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
        //return ['message' => 'Actualizar la información del usuario'];
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'cedula' => 'required|numeric|digits:8|unique:users,cedula,'.$user->id,
        ]);

        $user->update($request->all());

        return ['message' => 'Actualizar la información del usuario'];

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

        $user = User::findOrFail($id);

        $user->delete();

        return  ['message' => 'Usuario eliminado'];

    }

    public function search()
    {
        if($search = \Request::get('q')){
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%")
                        ->orWhere('type','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users =  User::orderBy('id','DESC')->paginate(2);
        }

        return $users;
    }
}
