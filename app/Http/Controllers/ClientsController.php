<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientResource;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    function listData(Request $request){
        return ClientResource::collection(User::where("role_id",UserRole::CLIENT)->get());
    }

    function storeAction(Request $request){
        $this->validate($request,[
            'email'=>'unique:users|required',
        ]);
        $user = User::findOrNew($request->get("id",null));
        $user->fill($request->all());
        $user->role_id = UserRole::CLIENT;
        $user->generateRandomPassword();
        $user->save();
        return ClientResource::make($user);
    }

    function storeData(Request $request){
        $user = User::findOrNew($request->get("id",null));
        return ClientResource::make($user);
    }
}
