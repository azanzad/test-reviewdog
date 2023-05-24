<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreatePasswordController extends Controller
{
    /**
     * CreatePassword function
     *
     * @param Request $request
     * @return Response
     */
    public function CreatePassword(Request $request)
    {
        $user = User::where('uuid', $request->get('id'))->firstOrFail();
        if (!empty(auth()->user())) {
            return to_route('home');
        } else {
            return view('auth.passwords.create_new_password', ['user' => $user]);
        }

    }
    /**
     * UpdateNewPassword function
     *
     * @param Request $request
     * @return Response
     */
    public function UpdateNewPassword(Request $request)
    {
        try {
            User::where('uuid', $request->uuid)->update(['password' => Hash::make($request->new_password), 'is_first_login' => 1]);
            //login this user
            $user = User::where('uuid', $request->uuid)->firstOrFail();
            $userdata = array(
                'email' => $user->email,
                'password' => $request->new_password,
            );
            Auth::attempt($userdata);
            return to_route('home')->with('login_success', trans('message.message.changed', ['module' => trans('message.label.password')]));

        } catch (\Exception$e) {
            return back()->withErrors(['message' => $e->getMessage()]);
        }

    }
}