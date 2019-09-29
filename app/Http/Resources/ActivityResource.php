<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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
        	'time'=> $this->time->format("H:i d.m.y"),
        ];
        if(!empty($this->user_id))
        	$data['user'] = [
        	    "name" =>$this->user->name,
            ];
        return $data;
    }
}
