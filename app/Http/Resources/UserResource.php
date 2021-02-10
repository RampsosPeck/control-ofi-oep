<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'cedula' => $this->cedula,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'type' => $this->type,
            'cargo' => $this->cargo,
            'bio' => $this->bio,
            'photo' => $this->photo
        ];
    }
}
