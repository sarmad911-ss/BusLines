<?php

namespace App\Models\trip;

use App\Models\Bus;
use App\Models\core\AcsimaModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TripBus
 * @package App\Models\trip
 * @property integer trip_id
 * @property integer bus_id
 * @property integer km_start
 * @property integer km_end
 * @property integer km_inside
 * @property integer work_hours
 */

class TripBus extends AcsimaModel
{

    protected $fillable = ['trip_id', 'bus_id', 'km_start', 'km_end', 'km_inside', 'work_hours'];


    public function bus()
    {
        return $this->hasOne(Bus::class, 'id', 'bus_id');
    }

    public function trip()
    {
        return $this->hasOne(Trip::class, 'id', 'trip_id');
    }

    public function getKmPassedAttribute()
    {
        return ($this->km_end - $this->km_start);
    }

    public function getKmDiffAttribute()
    {
        return ($this->trip->distanceF - $this->km_passed);
    }

    public function getKmOverInsideAttribute()
    {
        $kmOver = $this->km_inside - 500;
        return ($kmOver >= 0) ? $kmOver : 0 ;
    }



}
