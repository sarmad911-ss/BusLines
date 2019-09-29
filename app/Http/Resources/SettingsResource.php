<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'key' => $this->key,
            'value' => $this->value,
        ];
	    $data['last_update'] = ActivityResource::make($this->last_update);
	    return $data;
    }
}
