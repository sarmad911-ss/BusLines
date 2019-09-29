<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BusResource extends JsonResource
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
        $data['model'] = $this->model;
        $data['plate_number'] = $this->plate_number;
        $data['seats_quantity'] = $this->seats_quantity;
        $data['vin'] = $this->vin;
        $data['misc'] = $this->misc;
        $data['mileage_start'] = $this->mileage_start;
        $data['mileage'] = $this->mileage;
        $data['euro_norm_id'] = $this->euro_norm_id;
        $data['release_date'] = $this->release_date;
        $data['type_id'] = $this->type_id;
        $data['type'] = $this->type->name ?? null;
        $data['owner_id'] = $this->owner_id;
        $data['owner'] = $this->owner;
        $data['dates'] = $this->dates;
        $data['files'] = BusFileResource::collection($this->files);
        $data['profile_url'] = $this->profile_url;
        $data['store_url'] = $this->store_url;
        if(!empty($this->last_update)) {
            $data['last_update'] = ActivityResource::make($this->last_update);
        }
        return $data;
    }
}
