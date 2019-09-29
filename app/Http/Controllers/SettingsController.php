<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingsResource;
use App\Models\core\File;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    function listView(Request $request){
		return view("pages.settings.settings");
    }

    function listData(Request $request){
    	if(!$request->has("items"))
    		$settings = Settings::all();
    	else{
		    if(is_string($request->get("items")))
			    $items = [$request->get("items")];
		    $settings = Settings::whereIn('key',$items??$request->get('items'))->get();
	    }
    	return SettingsResource::collection($settings);
    }

    function storeAction(Request $request){
	    if(!$request->has("items"))
		    return response()->json(['error'=>"empty items"],422);
	    foreach ($request->get("items") as $item){
	        if(!empty($item['value']))
	    	    Settings::set($item['key'],$item['value']);
	        else{
	            Settings::where("key",$item['key'])->delete();
            }
	    }
	    $items = collect($request->get("items"))->pluck("key");
	    $settings = Settings::whereIn('key',$items??$request->get('items'))->get();
	    return SettingsResource::collection($settings);
    }

    function uploadLogoAction(Request $request){
    	if($request->hasFile("logo"))
	    {
	    	$file = new File();
	    	$file->file = $request->file("logo");
	    	$file->save();
	    	return response()->json(['data'=>['url'=>$file->src]]);
	    }
    	else
    		return response()->json(['data'=>['errors'=>['logo'=>['empty logo attribute']]]]);
    }
}
