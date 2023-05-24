<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use App\Models\OTP;
use App\Models\User;
use App\Models\Country;
use App\Models\Timezone;
use App\Jobs\SignupOtpQueue;
use Illuminate\Http\Request;
use App\Services\CompanyService;
use App\Services\SettingService;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Auth\Events\Registered;
use App\Services\SubscriptionPlanService;

class SignupController extends Controller
{
    protected $companyService;

    protected $subscriptionPlanService;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct(CompanyService $companyService, SubscriptionPlanService $subscriptionPlanService)
    {
        $this->middleware('guest');
        $this->companyService = $companyService;
        $this->subscriptionPlanService = $subscriptionPlanService;
    }


    /**
     *
     * Display a listing of the resource.
     * @param string $uuid
     * @return \Illuminate\Http\Response
     */
    public function index($uuid)
    {
        try{
            $plan = $this->subscriptionPlanService->getPlan($uuid);

            if(isset($plan) && !empty($plan)){
                $countries = (new SettingService())->getCountry();
                return view('signup.create', compact('countries','plan'));
            }
            return redirect()->route('login');

        } catch (\Throwable $th) {

            return redirect()->route('login')->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SignupRequest $request)
    {
        try{

            $user = $this->companyService->signupUser($request);

            //send verification link
            $user->sendEmailVerificationNotification();

            return redirect()->back()->with('success_modal', trans('message.message.signup_success'));

        } catch (\Throwable $th) {
            return redirect()->route('login')->with('error', $th->getMessage());
        }
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
    public function update(Request $request, $id)
    {
        //
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
}
