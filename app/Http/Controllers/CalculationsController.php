<?php

namespace App\Http\Controllers;

use App\Http\Resources\CalculationResource;
use App\Models\Calculation;
use Illuminate\Http\Request;

class CalculationsController extends Controller
{
    public function storeAction(Request $request){
        $calculation = Calculation::findOrNew($request->get("id",null));
        $calculation->fill($request->all());
        $calculation->save();
        return CalculationResource::make($calculation);
    }

    public function listView(){
        return view("pages.calculations.list");
    }

    public function storeData(Request $request){
        $calculation = Calculation::findOrNew($request->get("id",null));
        return CalculationResource::make($calculation);
    }

    public function listData(){
        return CalculationResource::collection(Calculation::paginate(9));
    }
}
