<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilsController extends Controller
{
	public function getRouteUrls(Request $request){
		if (empty($request->get("routes")))
			return response()->json(["errors"=>['routes'=>['Empty routes array']]],422);
		$routes = [];
		foreach ($request->get("routes") as $route){
			if(\Route::has($route))
				$routes[$route] = route($route);
		}
		return response()->json(["data"=>['routes'=>$routes]]);
	}
}
