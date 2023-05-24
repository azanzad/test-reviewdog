<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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

        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');

    }


    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        $this->middleware('auth');
        return $request->user()->hasVerifiedEmail()
                        ? redirect($this->redirectPath())
                        : view('auth.verify');
    }

    public function verify(Request $request, $id)
    {

        if(!empty($request->user()) && $id < 0){


            if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
                throw new AuthorizationException;
            }

            if (! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
                throw new AuthorizationException;
            }

            if ($request->user()->hasVerifiedEmail()) {
                return $request->wantsJson()
                            ? new JsonResponse([], 204)
                            : redirect($this->redirectPath());
            }

            if ($request->user()->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }

            if ($response = $this->verified($request)) {
                return $response;
            }

            return redirect()->route('home')->with('success', trans('message.message.verify_email'));

        }else{

            $user = User::find($id);

            if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
                throw new AuthorizationException;
            }

            if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
                throw new AuthorizationException;
            }

            // if ($user->hasVerifiedEmail()) {
            //     return $request->wantsJson()
            //                 ? new JsonResponse([], 204)
            //                 : redirect($this->redirectPath());
            // }

            if ($user->markEmailAsVerified()) {

                event(new Verified($user));
            }

            if ($response = $this->verified($request)) {
                return $response;
            }

            Auth::logout();

            return redirect()->route('login')->with('success', trans('message.message.verify_email'));
        }

    }
}
