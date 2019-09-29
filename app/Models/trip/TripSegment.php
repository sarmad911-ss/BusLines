<?php

namespace App\Models\trip;

use Illuminate\Database\Eloquent\Model;

class TripSegment extends Model
{
    protected $fillable = ['from', 'to', 'distance', 'inside', 'trip_id'];

    public function setDurationAttribute($duration)
    {
        $this->attributes['duration'] = json_encode($duration);
    }

    public function getDurationAttribute()
    {
        return json_decode($this->attributes['duration']);
    }
}
