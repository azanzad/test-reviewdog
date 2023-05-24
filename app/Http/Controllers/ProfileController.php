<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Services\CompanyService;
use App\Services\ProfileService;
use App\Services\SettingService;
use App\Services\CompanyCardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ChangePasswordRequest;
use ProtoneMedia\LaravelVerifyNewEmail\Http\InvalidVerificationLinkException;



class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = (new CompanyService())->getCompany(auth()->user()->uuid);
        $countries = (new SettingService())->getCountry();
        return view('profile.profile', ['user' => $user,'countries'=>$countries]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileUpdateRequest $request, $uuid)
    {
        $msg = trans('message.message.updated', ['module' => trans('message.label.profile')]);
        if (auth()->user()->email != $request->email) {
            $msg = trans('message.message.verify_mail_send');
        }
        try {
            $this->profileService->updateProfile($request, $uuid);
            return $this->success_response(200, $msg);
        } catch (\Exception$e) {
            return $this->error_response(400, $e->getMessage());
        }
    }
    public function changePassword()
    {
        return view('profile.change_password');
    }
    public function updatePassword(ChangePasswordRequest $request)
    {
        try {
            $this->profileService->changePassword($request);
            return $this->success_response(200, trans('message.message.changed', ['module' => trans('message.label.password')]));
        } catch (\Exception$e) {
            return $this->error_response(400, $e->getMessage());
        }

    }
    public function currentPlan()
    {
        $user = (new CompanyService())->getCompany(auth()->user()->uuid);
        $default_card = (new CompanyCardService())->getCustomerDefaultCard(auth()->user()->id);
        $current_subscription = DB::table('subscriptions')->where('id', auth()->user()->subscription_id)->first();

        return view('profile.current_plan', ['user' => $user, 'default_card' => $default_card, 'current_subscription' => $current_subscription]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
     * Mark the user's new email address as verified.
     *
     * @param  string $token
     *
     * @throws \ProtoneMedia\LaravelVerifyNewEmail\Http\InvalidVerificationLinkException
     */

    public function verify(string $token)
    {

        $user = app(config('verify-new-email.model'))->whereToken($token)->firstOr(['*'], function () {
            throw new InvalidVerificationLinkException(
                __('The verification link is not valid anymore.')
            );
        })->tap(function ($pendingUserEmail) {
            $pendingUserEmail->activate();
        })->user;

        if (config('verify-new-email.login_after_verification')) {
            //  Auth::guard()->login($user, config('verify-new-email.login_remember'));
        }
        //logout current user
        Auth::logout();
        Session::flush();

        return $this->authenticated();
    }

    protected function authenticated()
    {
        return redirect(config('verify-new-email.redirect_to'))->with('status', trans('auth.verified_email'));
    }
}
