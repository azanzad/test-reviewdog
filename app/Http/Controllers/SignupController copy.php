<?php

namespace App\Http\Controllers;

use App\Models\OTP;
use App\Models\User;
use App\Models\Country;
use App\Jobs\SignupOtpQueue;
use Illuminate\Http\Request;
use App\Services\CompanyService;
use App\Http\Requests\SignupRequest;
use Illuminate\Support\Facades\Crypt;
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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uuid = "4028974a-8f71-426e-b3c5-36e65554bf92";

        $countries = Country::get();

        $plan = $this->subscriptionPlanService->getPlan($uuid);

        return view('signup.create', compact('countries','plan'));
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
     * sendOtp function
     * @param Request $request
     * @return reponse()
     */
    public function sendOtp(SignupRequest $request){

        try{

            if($request->ajax()){

                $data = $request->all();

                $otp = $this->generateUniqueOtp();
                $token = Crypt::encryptString(json_encode($data));
                OTP::updateOrCreate(['email'=>$request->email],['otp'=>$otp]);

                $emailData = ['email'=>$request->email,'name'=>$request->name,'otp'=>$otp];
                $registrationEmailQueue = new SignupOtpQueue($emailData);
                dispatch($registrationEmailQueue)->delay(now()->addSeconds(3));

                return response()->json(['status'=>true,'token'=>$token], 200);
            }

        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }
    }

    /**
     * verifyOtp function
     * @param string $token
     * @return response
     */
    public function verifyOtp($token){

        return view('signup.otp_page', compact('token'));
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

            $decrypted = json_decode(Crypt::decryptString($request->token));
            $storeOtp = OTP::where('email', $decrypted->email)->value('otp');

            if($storeOtp == $request->enter_otp){
                //delete otp
                OTP::where('email', $decrypted->email)->delete();

                $newRequest = new \Illuminate\Http\Request();
                $newRequest->replace([
                    'name' => $decrypted->name,
                    'email' => $decrypted->email,
                    'contact_number' => $decrypted->contact_number,
                    'country_code' => $decrypted->country_code,
                    'card_holder_name' => $decrypted->card_holder_name,
                    'stripeToken' => $decrypted->stripeToken,
                    'planid' => $decrypted->planid,
                ]);

                $user = $this->companyService->signupUser($newRequest);
                $uuid = User::findOrFail($user->id);

                return response()->json(['status'=>true, 'uuid'=>$uuid->uuid,  'message'=>trans('message.message.added', ['module' => trans('message.label.customer')])], 200);
            }else{
                return response()->json(['status'=>false, 'message'=>trans('message.message.wrong_otp')], 200);
            }

        } catch (\Throwable $th) {
            return response()->json(['status'=>false, 'message'=>"Error :".$th->getMessage()], 400);
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

    /**
     * generateUniqueOtp function
     *
     * @return int
     */
    public function generateUniqueOtp()
    {
        do {
            $code = random_int(100000, 999999);
        } while (OTP::where("otp", "=", $code)->first());

        return $code;
    }

    public function otp_page(){

        return view('signup/otp_index');
    }

    /**
     * applyPromotionCode function
     * @param Request $request
     * @return void
     */
    public function applyPromotionCode(Request $request){

        try{

            if($request->ajax()){

                $data = $request->all();

                return response()->json(['status'=>true,'token'=>$token], 200);
            }

        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }
    }
}
