<?php

namespace App\Http\Controllers;

use App\Helpers\SendMail;
use App\Models\ForgotRequest;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

/**
 * Class AuthController
 * @package App\Http\Controllers
 *
 * Auth info stored in session by key 'user'
 * User role can find in session()->get('user')->role_id
 * UserRole::ADMIN and UserRole::USER defined already
 */
class AuthController extends Controller
{
    public function loginView()
    {
        return view('pages.auth.login');
    }

    public function loginAction(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|max:255|email',
            'password' => 'required|max:255',
        ]);
        $user = User::where('email', $request->email)->first();
        if ((empty($user)) || (!\Hash::check($request->password, $user->password))) {
            return response()->json(['errors'=>['password'=>['Falscher Benutzer oder Passwort']]],422);
        }
        session()->put('user', $user);
        session()->save();
        return response()->json(['redirect'=>route('indexPage')]);
    }

    public function forgotPasswordAction(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|max:255',
        ]);
        $user = User::where('email', $request->email)->first();
        if (empty($user)) {
	        return response()->json(['errors'=>['email'=>['Kein solcher Benutzer']]],422);
        }
        $forgot = ForgotRequest::where('user_id', $user->id)->first();
        if (!empty($forgot)) {
            $forgot->delete();
        }
        $forgot = new ForgotRequest();
        $forgot->user_id = $user->id;
        $forgot->key = ForgotRequest::generateKey();
        $forgot->save();
        SendMail::sendEmail($user->email, 'Passwort vergessen', 'mail.forgotPassword', ['key' => $forgot->key]);
	    return response()->json(['notification'=>'Mail mit Reset-Link wurde gesendet. Posteingang überprüfen']);
    }

    public function forgotRequestView(Request $request)
    {
	    $forgot = ForgotRequest::where('key', $request->key)->first();
        if (empty($forgot)) {
            return view('pages.error', ['message' => 'Ungültiger vergessener Schlüssel. Vielleicht hast du eine neue E-Mail?']);
        } else {
        	$data['key'] = $request->key;
            return view('pages.auth.forgotRequest', $data);
        }
    }

    public function changePasswordAction(Request $request)
    {
        $request->validate([
            'key' => 'bail|required|max:255',
            'password' => 'required|max:255',
        ]);
	    $forgot = ForgotRequest::where('key', $request->key)->first();
	    $user = User::find($forgot->user_id);
        if (empty($user)) {
	        return response()->json(['errors'=>['password'=>['No such user']]],422);
        }
        $user->password = $request->password;
        $user->save();
        $forgot->delete();
        SendMail::sendEmail($user->email, 'Erfolgreiches Zurücksetzen des Passworts', 'mail.info');
        session()->put("notification","Passwort wurde geändert");
	    return response()->json(['success'=>true]);
    }

    /**
     * All users logout makes through this method
     */
    public function logout()
    {
        session()->forget('user');
        session()->save();
        return redirect()->route('loginView');
    }
}
