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
            'codigo' => $this->codigo,
            'dates' => [
                'fecha_orden'      => $this->fecha_orden->diffForHumans(),
                'fecha_orden_data' => $this->fecha_orden
            ],
            'fecha_entrega' => $this->fecha_entrega,
            'total_bs' => $this->total_bs,
            'delivery' => $this->delivery,
            'estado' => $this->estado,
            'especificacion' => $this->especificacion,
            'nomdelivery' => $this->nomdelivery,
            'user' => new UserResource($this->user),
            'carritodetalle' => CarritoResource::collection($this->carrito_detalles),
        ];
    }
}
