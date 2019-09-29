<?php

namespace App\Models\trip;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TripFile
 * @package App\Models\trip
 * @property integer trip_id
 * @property integer file_id
 */

class TripFile extends Model
{
    protected $fillable = ['trip_id', 'file_id'];

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public static function store($tripId, $fileId)
    {
        $relation = TripFile::where('trip_id', $tripId)->where('file_id', $fileId)->get();
        if($relation->isEmpty()){
            $relation = new TripFile();
            $relation->trip_id = $tripId;
            $relation->file_id= $fileId;
            $relation->save();
        }
        if($relation instanceof TripFile){
            $operation = 'create';
        }
        else{
            $operation = 'edit';
        }
        return $operation;
    }
}
