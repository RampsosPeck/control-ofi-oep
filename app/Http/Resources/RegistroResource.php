<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistroResource extends JsonResource
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
            'fecha'   => $this->fecha,
            'llegadam'=> $this->llegadam,
            'retirom' => $this->retirom,
            'atraso1' => $this->atraso1,
            'llegadat'=> $this->llegadat,
            'retirot' => $this->retirot,
            'atraso2'  => $this->atraso2,
            'user' => new UserResource($this->user),
            'horario' => new HorarioResource($this->horario)
        ];
    }
}
