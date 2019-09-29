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
        $data =  parent::toArray($request);
        $data['photo_url'] = $this->photo_url;
        $data['dob'] = $this->dob ? $this->dob->format("Y-m-d") : null;
        $data['dob_formatted'] = $this->dob ? $this->dob->format("d/m/Y") : null;
        if($this->can_edit)
            $data['edit_link'] = route("storeUserView",$this);
        $data['can_delete'] = $this->can_delete;
        if(!empty($this->last_update)) {
            $data['last_update'] = ActivityResource::make($this->last_update);
        }
        return $data;
    }
}
