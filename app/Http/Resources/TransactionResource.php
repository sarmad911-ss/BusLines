<?php

namespace App\Http\Resources;

use App\Http\Resources\trip\TripFileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
        $data['date'] = $this->date ?? null;
        $data['purpose'] = $this->purpose ?? null;
        $data['file'] = TripFileResource::make($this->file ?? null);
        if(!empty($this->last_update)) {
            $data['last_update'] = ActivityResource::make($this->last_update);
        }
        return $data;
    }
}
