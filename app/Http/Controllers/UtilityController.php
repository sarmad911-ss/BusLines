<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UtilityController extends Controller
{
    public function roadmapView(){
    	return view("pages.roadmap");
    }
}
