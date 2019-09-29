<?php

namespace App\Models;

use App\Models\core\AcsimaModel;

class Settings extends AcsimaModel
{
    protected $fillable = ['key',"value"];

    public function setValueAttribute($val){
    	$this->attributes['value'] = json_encode($val);
    }

    public function getValueAttribute(){
    	return json_decode($this->attributes['value'] ?? '""',1);
    }

    public static function set($key,$value){
	    $setting = Settings::where("key",$key)->get();
	    if($setting->count()==0)
	    	$setting = new Settings();
	    else
	    	$setting = $setting->first();
	    $setting->fill(['key'=>$key,'value'=>$value]);
	    $setting->save();
    }

    public static function get($key,$default){
		$setting = Settings::where("key",$key)->get();
		if($setting->count()==0)
			return $default;
		return $setting->first()->value;
    }

    public static function getValueFromDataByKey(string $key, array $settingsData)
    {
        $value = false;
        if(empty($settingsData) || empty($settingsData[0]))
            return $value;
        foreach ($settingsData as $data){
            if(!empty(array_search($key, $data))){
                $setting = $data;
            }
        }
        return $setting['value'] ?? '';
    }
}
