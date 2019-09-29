<?php

namespace App\Http\Resources\trip;

use Illuminate\Http\Resources\Json\JsonResource;

class BusSelectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['type'] = $this->type->name ?? null;
        return $data;
    }
}
