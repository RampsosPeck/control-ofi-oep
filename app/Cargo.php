<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $fillable = [
        'nombre','fecha'
    ];

    protected $dates = [
        'fecha'
    ];
}
