<?php

namespace App\Models;

use App\Models\core\AcsimaModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BusFile
 * @package App\Models
 * @property integer id
 * @property integer bus_id
 * @property integer file_id
 */

class BusFile extends Model
{
    protected $fillable = ['bus_id', 'file_id'];

    protected $primaryKey = null;
    public $incrementing = false;

    public static function store($busId, $fileId)
    {
        $relation = BusFile::where('bus_id', $busId)->where('file_id', $fileId)->get();
        if($relation->isEmpty()){
            $relation = new BusFile();
            $relation->bus_id = $busId;
            $relation->file_id= $fileId;
            $relation->save();
        }
        if($relation instanceof BusFile){
            $operation = 'create';
        }
        else{
            $operation = 'edit';
        }
        return $operation;
    }
}
