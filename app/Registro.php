<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
	protected $fillable = [
        'user_id','fecha','horario_id','llegadam','retirom','llegadat','retirot','atraso'
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }
}
