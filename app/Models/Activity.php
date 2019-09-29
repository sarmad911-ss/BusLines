<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['id','table','row_id','time','user_id'];

    public function user(){
    	return $this->belongsTo(User::class,"user_id");
    }


	public function getTimeAttribute(){
		if(!empty($this->attributes['time']))
			return Carbon::createFromTimestamp($this->attributes['time']);
		return null;
	}
}
