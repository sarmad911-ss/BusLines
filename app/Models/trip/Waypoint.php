<?php

namespace App\Models\trip;

use App\Models\core\AcsimaModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Waypoint
 * @package App\Models\trip
 * @property integer id
 * @property integer trip_id
 * @property string location
 * @property string name
 * @property boolean border
 * @property integer position
 */

class Waypoint extends AcsimaModel
{
    protected $fillable = ['trip_id', 'location', 'name','type','comment','date'];

    public function setLocationAttribute($location)
    {
        $this->attributes['location'] = json_encode($location);
    }

    public function getLocationAttribute()
    {
        return json_decode($this->attributes['location']);
    }

    public function setPositionAttribute($positon)
    {
        if(empty($this->id)){
            $positon = $positon ?? Waypoint::where('trip_id', $this->trip_id)->max('position') + 1;
        }
        $this->attributes['position'] = $positon;
    }

    public function setDateAttribute($value){
        $this->attributes['date'] = Carbon::createFromFormat(config("app.datepicker_format"),$value)->timestamp;
    }

    public function getDateAttribute(){
        if(empty($this->attributes['date']))
            return null;
        return Carbon::createFromTimestamp($this->attributes['date'])->format(config('app.datepicker_format'));
    }


}
