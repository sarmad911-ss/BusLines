<?php

namespace App\Http\Resources\trip;

use App\Http\Resources\ActivityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WaypointResource extends JsonResource
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
        if(!empty($this->last_update)) {
            $data['last_update'] = ActivityResource::make($this->last_update);
        }
        return $data;
    }
}
