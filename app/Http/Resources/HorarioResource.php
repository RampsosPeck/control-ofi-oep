<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HorarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'fecha'  => $this->fecha,
            'ingresom'  => $this->ingresom,
            'salidam'  => $this->salidam,
            'ingresot'  => $this->ingresot,
            'salidat'  => $this->salidat,
            'estado'  => $this->estado,
        ];
    }
}
