<?php

namespace App\Http\Controllers;


use DataTables;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\CompanyService;
use App\Services\CompanyCardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\AddCardInfoRequest;
use App\Services\SubscriptionPlanService;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;


class CompanyCardController extends Controller
{

    protected $companyCardService;

    protected $companyService;

    protected $subscriptionPlanService;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct(CompanyCardService $companyCardService, CompanyService $companyService, SubscriptionPlanService $subscriptionPlanService)
    {
        $this->companyCardService = $companyCardService;
        $this->companyService = $companyService;
        $this->subscriptionPlanService = $subscriptionPlanService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->companyCardService->getData($request);
            return $this->initDataTable($data);
        } else {
            return view('cards.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $intent = auth()->user()->createSetupIntent();
        $payment_type = !empty($_GET['type']) ? base64_decode($_GET['type']) : '';
        return view('cards.create', ['intent' => $intent, 'payment_type' => $payment_type]);
    }
    public function createPayment(Request $request)
    {
        $cards = (new CompanyCardService())->getData($request)->get();
        return view('cards.create_payment', ['cards' => $cards]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $this->companyCardService->storeCard($request);
            $msg = trans('message.message.added', ['module' => trans('message.label.card')]);

            if ($request->type == 'payment') {
                $msg = trans('message.message.activated', ['module' => trans('message.label.subscription')]);
            }
            return $this->success_response(200, $msg);

        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function makePayment(Request $request)
    {
        try {
            $this->companyCardService->makePayment($request);
            return $this->success_response(200, trans('message.message.activated', ['module' => trans('message.label.subscription')]));

        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }
    }
    /**
     * changeDefaultCard function
     *
     * @param Request $request
     * @return void
     */
    public function changeDefaultCard(Request $request)
    {
        $this->companyCardService->updateDefaultCard($request);
        return $this->success_response(200, trans('message.message.set_default_card'));
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
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $this->companyCardService->deleteCompanyCard($uuid);
        return $this->success_response(200, trans('message.message.deleted', ['module' => trans('message.label.card')]));

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function initDataTable($data)
    {
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('cardholder_name', function ($value) {
                return '<span  data-toggle="tooltip"  title="' . $value->cardholder_name . '">' . mb_strimwidth($value->cardholder_name, 0, 25, "...") . '</span>';
            })
            ->editColumn('brand', function ($value) {
                return $value->brand . ' ****' . $value->last_number;
            })
            ->editColumn('is_primary', function ($value) {
                return view('cards.getdefault_card', ['value' => $value]);

            })
            ->editColumn('status', function ($value) {
                return view('cards.getstatus', ['value' => $value]);
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('d/m/Y h:i:s A');
            })
            ->addColumn('action', function ($value) {
                return view('cards.action-button', ['value' => $value]);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * addUserCardDetails function
     *
     * @return void
     */
    public function addUserCard(){

        try{

            $user = Auth::user();

            if( ($user->role == config('params.admin_role'))  || ($user->role == config('params.company_role') && $user->subscription_id) ){

                return redirect()->route('home');
            }
            $plan = $this->subscriptionPlanService->getPlanById($user->planid);

            if(isset($plan) && !empty($plan)){

                return view('signup.add_card', compact('plan'));
            }
            return view('signup/add_card');

        } catch (\Throwable $th) {

            return redirect()->route('login')->with('error', $th->getMessage());
        }
    }


    /**
     * storeUserCard function
     *
     * @param AddCardInfoRequest $request
     * @return response
     */
    public function storeUserCard(AddCardInfoRequest $request){
        try{

            $this->companyCardService->storeCard($request);

            $msg = trans('message.message.activated', ['module' => trans('message.label.subscription')]);
            Session::flash('success', $msg);

            return response()->json(['status'=>true],200);

        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }
    }

     /**
     * applyPromotionCode function
     * @param Request $request
     * @return void
     */
    public function applyPromotionCode(Request $request){

        try{

            if($request->ajax()){

                $promotionCode = $this->companyCardService->findActivePromotionCode($request->promocode);

                if(empty($promotionCode)){

                    return response()->json(['status'=>false,'message'=>trans('message.message.wrong_promocode')], 200);
                }
                else if($promotionCode->active == false){

                    return response()->json(['status'=>false,'message'=>trans('message.message.expire_promo')], 200);
                }
                else if(!empty($promotionCode->expires_at) && $promotionCode->expires_at < Carbon::now()->timestamp){

                    return response()->json(['status'=>false,'message'=>trans('message.message.expire_promo')], 200);
                }else{

                    $disscount = 0.00;
                    $totalAmount = $request->plan_amount;
                    //fix amount promocode
                    if($promotionCode->coupon->amount_off > 0){
                        $disscount = round($promotionCode->coupon->amount_off, 2);
                        $amount = (float) $request->plan_amount - $disscount;
                        $totalAmount = number_format((float)$amount, 2, '.', '');
                    }

                    //percent amount promocde
                    if($promotionCode->coupon->percent_off > 0){

                        $disscount = (((float) $request->plan_amount) * $promotionCode->coupon->percent_off) / 100;
                        $disscount = round($disscount,2);
                        $amount = (float) $request->plan_amount - $disscount;
                        $totalAmount = number_format((float)$amount, 2, '.', '');
                    }

                    $promotionToken = [
                        'promotion_id' => $promotionCode->id,
                        'promotion_code' => $promotionCode->code,
                        'discount_amount' => $disscount
                    ];
                    $promotionToken = Crypt::encryptString(json_encode($promotionToken));
                    return response()->json([
                        'status'=>true,
                        'total_amount' => $totalAmount,
                        'disscount_amount' => number_format((float)$disscount, 2, '.', ''),
                        'message'=>trans('message.message.appy_promocode'),
                        'promotionToken'=>$promotionToken], 200);
                }
            }

        } catch (\Throwable$th) {
            return $this->error_response(400, $th->getMessage());
        }
    }
}
