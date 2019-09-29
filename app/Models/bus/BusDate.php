<?php

namespace App\Models;

use App\Models\core\AcsimaModel;
use Carbon\Carbon;

/**
 * Class BusDate
 * @package App\Models
 * @property integer id
 * @property integer bus_id
 * @property integer date
 * @property string name
 */
class BusDate extends AcsimaModel
{
    protected $fillable = ['bus_id', 'date', 'name'];

    protected static $start_dates_name = [
        'SP', 'HU/Tüv', 'Feuerlöscher', 'Verbands-kasten', 'Ölwechsel', 'Getriebe'
    ];

    public static function getDefaultDates()
    {
        $defaultDates = [];
        foreach (BusDate::$start_dates_name as $datesName){
            $defaultDates[]['name'] = $datesName;
        }
        return $defaultDates;
    }

    public function setDateAttribute($date)
    {
        if(!empty($date))
            $this->attributes['date'] = (new Carbon($date))->timestamp;
    }

    public function getDateAttribute()
    {
        $date = $this->attributes['date'];
        if(!empty($date))
            $date = Carbon::createFromTimestamp($date)->format('Y-m-d');
        return $date;
    }

    public static function getDatesArray()
    {
        $dates = [];
        foreach (BusDate::$start_dates_name as $k => $dateName){
            $dates[$k]['name'] = $dateName;
        }
        return $dates;
    }

}
