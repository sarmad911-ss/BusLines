<?php

namespace App\Http\Controllers;

use App\Helpers\SendMail;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserRoleResource;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class UsersController extends Controller
{
	function listView(Request $request){
		return view("pages.users.list");
	}

    function storeView(Request $request,User $user){
		return view("pages.users.profile",['user'=>$user]);
    }

    function storeAction(Request $request){
		$user = User::where("email", $request->get("email"));
		if($request->has("id"))
			$user = $user->where("id","!=",$request->get("id"));
		$user = $user->get();
		if($user->count())
			return response()->json(['data'=>['errors'=>['email'=>['User with same email already exists']]]],422);

		$user = User::findOrNew($request->get("id") ?? null);
		$user->fill($request->all());
		if(empty($user->role_id))
			$user->role_id = UserRole::EMPLOYEE;
        if(empty($request->get("id"))){
            $newpassword = $user->generateRandomPassword();
        }
		$user->save();
        if($user->id==session()->get("user")->id)
            session()->put("user",$user);
        if(empty($request->get("id"))){
            SendMail::sendEmail($user->email,"Данные для входа","mail.LoginData",['email'=>$user->email,'password'=>$newpassword]);
        }
        session()->put("notification",'Изменения сохранены');
		return UserResource::make($user);
    }

    function singleData(Request $request){
		return UserResource::make(User::findOrNew($request->get("id") ?? null));
    }

    function listData(Request $request){
	    return UserResource::collection(User::whereIn('role_id',[UserRole::ADMIN,UserRole::EMPLOYEE])->orderBy("name")->paginate(config('app.paginate')));
    }

    function removeAction(Request $request){
		$user = User::find($request->id);
		if($user->can_delete)
			$user->delete();
		return redirect()->back();
    }

    function checkEmailAction(Request $request){
		$exists = User::where(["email"=>$request->get("email")]);
		if($request->has("id"))
			$exists = $exists->where("id","!=",$request->get("id"));
		$exists = $exists->get()->count()>0;
		if($exists)
			return response()->json(['data'=>['errors'=>['email'=>['User with same email already exists']]]]);
		else
			return response()->json(['data'=>['exists'=>false]]);
    }

    function getRolesData(Request $request){
		return UserRoleResource::collection(UserRole::orderBy("id")->get());
    }

    public function listClientsData(Request $request)
    {
        $clients = User::where('role_id', UserRole::CLIENT)->orderBy('name')->get();
        return UserResource::collection($clients);
    }

    public function listOwnersData(Request $request)
    {
        $owners = User::where('role_id', UserRole::FRIEND)->orderBy('name')->get();
        return UserResource::collection($owners);
    }

}
