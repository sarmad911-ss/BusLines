<?php

namespace App\Http\Resources\trip;

use App\Http\Resources\ActivityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TripBusResourse extends JsonResource
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
        $data['km_passed'] = $this->km_passed;
        $data['km_diff'] = $this->km_diff;
        $data['km_over_inside'] = $this->km_over_inside;

        /** data from bus */
        $data['model'] = $this->bus->model;
        $data['type'] = $this->bus->type->name;
        $data['plate_number'] = $this->bus->plate_number;
        $data['seats_quantity'] = $this->bus->seats_quantity;
        $data['profile_url'] = $this->bus->profile_url;
        $data['store_url'] = $this->bus->store_url;
        if(!empty($this->last_update)) {
            $data['last_update'] = ActivityResource::make($this->last_update);
        }
        return $data;
    }
}
