<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
	protected $fillable = [
        'user_id','fecha','horario_id','llegadam','retirom','atraso1','llegadat','retirot','atraso2'
    ];

    //protected $dates = ['llegadam','retirom','llegadat','retirot'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }
}
