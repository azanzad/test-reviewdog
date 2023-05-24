<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = RouteServiceProvider::HOME;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // public function authenticated(Request $request, $user)
    // {
    //     if ($user->status == config('params.in_active') || $user->status == config('params.deleted')) {
    //         Auth::guard('web')->logout();
    //         \Session::flash('error', 'Error!');
    //         \Session::flash('alert-class', 'text-danger');
    //         \Session::flash('message', 'Your account is inactive please contact to administration.');
    //         return redirect('/login');
    //     } elseif ($user->role == config('params.customer_role')) {
    //         Auth::guard('web')->logout();
    //         \Session::flash('error', 'Error!');
    //         \Session::flash('alert-class', 'text-danger');
    //         \Session::flash('message', 'These credentials do not match our records.');
    //         return redirect('/login');

    //     } else {
    //         if (!$user->is_first_login) {
    //             Auth::guard('web')->logout();
    //             return to_route('create_password', ['id' => $user->uuid]);
    //         }
    //         return to_route('home');
    //     }
    // }


    public function loginform(){

        return view('auth.login');
    }


     /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {

        $this->validateLogin($request);

        
        //check user role and status
        if (!$this->checkRoleForLogin($request)) {
            
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }
        
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {

            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }
            // $request->fulfill();
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function checkRoleForLogin($request)
    {
        $user = User::where('email', $request->email)->first();
        if (!empty($user)) {
            
            //allow active user only
            if ( $user->status != Config::get('params.active') ) {
                
                throw ValidationException::withMessages([
                    $this->username() => ['Your account is not activated yet, please contact to administrator'],
                ]);
            }
            
            return true;
        }
        
        return false;
    }
}
