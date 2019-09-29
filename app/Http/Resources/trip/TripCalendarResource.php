<?php

namespace App\Http\Resources\trip;

use Illuminate\Http\Resources\Json\JsonResource;

class TripCalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data['id'] = $this->id ?? null;
        $data['status'] = $this->activeStatus ?? $this->status;
        $data['name'] = $this->name ?? null;
        $data['start_date'] = $this->start_date ?? null;
        $data['end_date'] = $this->end_date ?? null;
        $data['buses'] = [];
        foreach ($this->buses as $bus){
            $data['buses'][]['plate_number'] = $bus->plate_number;
        }

        $data['client'] = $this->client;

        $data['cost'] = $this->cost ?? null;
        $data['paid'] = $this->paid ?? null;
        $data['calendar_day'] = $this->calendar_day ?? null;
        $data['calendar_date'] = $this->calendar_date ?? null;
        $data['calendar_type'] = $this->calendar_type ?? null;
        $data['profile_url'] = $this->profile_url ?? null;
        return $data;
    }
}
